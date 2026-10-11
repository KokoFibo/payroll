<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiSqlService
{
    /** Tabel di allowed_tables yang tidak terbaca (salah nama / belum di-GRANT). */
    public array $missing = [];

    private function db()
    {
        return DB::connection(config('ai-query.connection'));
    }

    /** Ringkasan skema (hanya tabel whitelist, tanpa kolom sensitif) untuk prompt. */
    public function schema(): string
    {
        $tables = config('ai-query.allowed_tables');
        $hidden = config('ai-query.hidden_columns');
        $in     = implode(',', array_fill(0, count($tables), '?'));

        $rows = $this->db()->select(
            "SELECT TABLE_NAME AS t, COLUMN_NAME AS c, COLUMN_TYPE AS ty
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME IN ($in)
             ORDER BY TABLE_NAME, ORDINAL_POSITION",
            $tables
        );

        $out   = [];
        $names = [];
        foreach ($rows as $r) {
            if (in_array(strtolower($r->c), $hidden, true)) {
                continue;
            }
            $out[$r->t][]   = "{$r->c} {$r->ty}";
            $names[$r->t][] = $r->c;
        }

        $seen          = array_map('strtolower', array_keys($names));
        $this->missing = array_values(array_filter($tables, fn ($t) => ! in_array(strtolower($t), $seen, true)));

        $text = collect($out)
            ->map(fn ($cols, $t) => "$t(" . implode(', ', $cols) . ')')
            ->implode("\n");

        $relations = $this->relations($names);
        $samples   = $this->samples($names);

        if ($relations) {
            $text .= "\n\nRelasi antar tabel (untuk JOIN):\n" . implode("\n", $relations);
        }
        if ($samples) {
            $text .= "\n\nData contoh tabel referensi (gunakan untuk mencocokkan nama yang disebut pengguna):\n" . $samples;
        }

        return $text;
    }

    /** Contoh isi tabel referensi (lookup) agar AI tahu nilai nyata seperti nama perusahaan. */
    private function samples(array $names): string
    {
        $lookup = array_map('strtolower', config('ai-query.lookup_tables', []));
        $limit  = (int) config('ai-query.lookup_sample_rows', 50);
        $blocks = [];

        foreach ($names as $table => $cols) {
            if (! in_array(strtolower($table), $lookup, true)) {
                continue;
            }

            // Kolom sensitif sudah tersaring di $names; buang juga kolom timestamp
            $cols = array_values(array_filter($cols, fn ($c) => ! preg_match('/_at$/i', $c)));
            if (! $cols) {
                continue;
            }

            try {
                $select = implode(', ', array_map(fn ($c) => '`' . str_replace('`', '', $c) . '`', $cols));
                $rows   = $this->db()->select("SELECT $select FROM `$table` LIMIT $limit");
            } catch (\Throwable) {
                continue;
            }

            $lines = [];
            foreach ($rows as $r) {
                $lines[] = collect((array) $r)
                    ->map(fn ($v, $k) => "$k=" . mb_strimwidth((string) $v, 0, 40, '…'))
                    ->implode(', ');
            }

            $blocks[] = "$table:\n" . implode("\n", $lines);
        }

        return implode("\n\n", $blocks);
    }

    /**
     * Deteksi relasi dari nama kolom: company_id -> companies.id, id_karyawan -> karyawans.id(_karyawan).
     * Bisa ditambah/ditimpa lewat config('ai-query.relations').
     *
     * @param  array<string, string[]>  $names  tabel => daftar kolom
     * @return string[]
     */
    private function relations(array $names): array
    {
        $tables = [];
        foreach (array_keys($names) as $t) {
            $tables[strtolower($t)] = $t;
        }

        $lines = [];
        foreach (config('ai-query.relations', []) as $from => $to) {
            $lines[$from] = "$from -> $to";
        }

        foreach ($names as $table => $cols) {
            foreach ($cols as $col) {
                if (isset($lines["$table.$col"])) {
                    continue;
                }

                $base = null;
                $byPrefix = false;
                if (preg_match('/^(.+)_id$/i', $col, $m)) {
                    $base = strtolower($m[1]);
                } elseif (preg_match('/^id_(.+)$/i', $col, $m)) {
                    $base = strtolower($m[1]);
                    $byPrefix = true;
                }
                if (! $base) {
                    continue;
                }

                $plural = str_ends_with($base, 'y') ? substr($base, 0, -1) . 'ies' : $base . 's';

                foreach ([$plural, $base, $base . 's'] as $candidate) {
                    if (isset($tables[$candidate]) && $tables[$candidate] !== $table) {
                        $target    = $tables[$candidate];
                        $targetCol = ($byPrefix && in_array($col, $names[$target], true)) ? $col : 'id';
                        $lines["$table.$col"] = "$table.$col -> $target.$targetCol";
                        break;
                    }
                }
            }
        }

        return array_values($lines);
    }

    private function hints(): string
    {
        $hints = array_filter((array) config('ai-query.hints', []));

        return $hints ? "\nPetunjuk tambahan:\n- " . implode("\n- ", $hints) : '';
    }

    /** @return array{sql:string, explanation:string, missing:array} */
    public function generate(string $question): array
    {
        $system = <<<TXT
Kamu adalah penerjemah bahasa alami ke SQL MySQL 8.
Aturan:
- Hanya hasilkan SATU statement SELECT (boleh WITH ... SELECT). Dilarang INSERT/UPDATE/DELETE/DDL.
- Hanya gunakan tabel dan kolom pada skema di bawah. Jangan mengarang kolom.
- Jangan menyertakan kolom primary key bernama persis `id` di SELECT. Kolom lain seperti `id_karyawan` BOLEH ditampilkan. Kolom `id` tetap boleh dipakai untuk JOIN, WHERE, dan ORDER BY.
- Hindari SELECT *; sebutkan kolom yang relevan saja.
- Jika pengguna menyebut nama perusahaan/departemen/jabatan/penempatan (mis. "company das"), cocokkan dengan data contoh tabel referensi, lalu filter lewat JOIN pada kolom nama/kode tabel referensi itu dengan LIKE (mis. companies.nama LIKE '%das%'). Jangan memfilter kolom *_id dengan teks.
- Gunakan bagian "Relasi antar tabel" untuk JOIN. Untuk kolom foreign key (company_id, department_id, jabatan_id, placement_id, dst.), tampilkan NAMA dari tabel referensinya lewat JOIN, bukan angka id-nya.
- Tanpa komentar SQL, tanpa titik koma.
- Jika pertanyaan tidak bisa dijawab dengan skema ini, kembalikan sql kosong dan jelaskan di explanation.
- Balas HANYA JSON: {"sql": "...", "explanation": "penjelasan singkat dalam Bahasa Indonesia"}

Skema:
{$this->schema()}
{$this->hints()}
TXT;

        $res = Http::withHeaders(['x-goog-api-key' => config('ai-query.api_key')])
            ->timeout(30)
            ->post('https://generativelanguage.googleapis.com/v1beta/models/' . config('ai-query.model') . ':generateContent', [
                'systemInstruction' => ['parts' => [['text' => $system]]],
                'contents'          => [['role' => 'user', 'parts' => [['text' => $question]]]],
                'generationConfig'  => ['temperature' => 0, 'responseMimeType' => 'application/json'],
            ]);

        if ($res->failed()) {
            throw new RuntimeException('Gemini error: ' . ($res->json('error.message') ?? $res->status()));
        }

        $json = json_decode($res->json('candidates.0.content.parts.0.text') ?? '', true);

        if (! is_array($json)) {
            throw new RuntimeException('Respons Gemini tidak valid.');
        }

        return [
            'sql'         => trim($json['sql'] ?? ''),
            'explanation' => $json['explanation'] ?? '',
            'missing'     => $this->missing,
        ];
    }

    /** Validasi ketat: hanya SELECT, tabel whitelist, tanpa kolom/keyword berbahaya. */
    public function validate(string $sql): string
    {
        $sql = rtrim(trim($sql), ";\s");

        if ($sql === '') {
            throw new RuntimeException('Tidak ada query yang dihasilkan.');
        }
        if (! preg_match('/^\s*(select|with)\b/i', $sql)) {
            throw new RuntimeException('Hanya query SELECT yang diperbolehkan.');
        }
        if (str_contains($sql, ';') || preg_match('/(--|\/\*|#)/', $sql)) {
            throw new RuntimeException('Query mengandung karakter yang tidak diperbolehkan.');
        }
        if (preg_match('/\b(insert|update|delete|drop|alter|truncate|create|replace|grant|revoke|call|exec|set|lock|handler|outfile|dumpfile|load_file|sleep|benchmark|information_schema|performance_schema|mysql|sys)\b/i', $sql)) {
            throw new RuntimeException('Query mengandung kata kunci yang diblokir.');
        }

        // Tabel pada FROM / JOIN harus whitelist (alias CTE diabaikan)
        $ctes = [];
        if (preg_match_all('/\b([a-z0-9_]+)\s+as\s*\(/i', $sql, $m)) {
            $ctes = array_map('strtolower', $m[1]);
        }
        // EXTRACT(... FROM kolom) / TRIM(... FROM ...) bukan nama tabel (kecuali berisi subquery)
        $forTables = preg_replace_callback(
            '/\b(?:extract|trim|substring)\s*\((?:[^()]|\([^()]*\))*\)/i',
            fn ($x) => preg_match('/\bselect\b/i', $x[0]) ? $x[0] : ' ',
            $sql
        );
        preg_match_all('/\b(?:from|join)\s+`?([a-z0-9_]+)`?/i', $forTables, $m);
        $allowed = array_map('strtolower', config('ai-query.allowed_tables'));
        foreach ($m[1] as $table) {
            $t = strtolower($table);
            if (! in_array($t, $allowed, true) && ! in_array($t, $ctes, true)) {
                throw new RuntimeException("Tabel '$table' tidak diizinkan.");
            }
        }

        foreach (config('ai-query.hidden_columns') as $col) {
            if (preg_match('/\b' . preg_quote($col, '/') . '\b/i', $sql)) {
                throw new RuntimeException("Kolom '$col' tidak diizinkan.");
            }
        }

        return $sql;
    }

    /** @return array{columns:array, rows:array} */
    public function run(string $sql): array
    {
        $sql = $this->validate($sql);
        $max = (int) config('ai-query.max_rows');

        // Batas waktu eksekusi 15 detik (MySQL 5.7.8+/8; diabaikan MariaDB)
        $sql = preg_replace('/^\s*select\b/i', 'SELECT /*+ MAX_EXECUTION_TIME(15000) */', $sql, 1);

        // Kolom yang tidak boleh tampil, termasuk kolom sensitif (aman walau AI memakai SELECT *)
        $hide = array_map('strtolower', array_merge(
            config('ai-query.hidden_output_columns', []),
            config('ai-query.hidden_columns', [])
        ));

        $rows = [];
        foreach ($this->db()->cursor($sql) as $row) {
            $rows[] = array_filter(
                (array) $row,
                fn ($key) => ! in_array(strtolower($key), $hide, true),
                ARRAY_FILTER_USE_KEY
            );

            if (count($rows) >= $max) {
                break;
            }
        }

        return ['columns' => $rows ? array_keys($rows[0]) : [], 'rows' => $rows];
    }
}
