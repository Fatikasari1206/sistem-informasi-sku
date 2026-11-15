<?php

namespace App\Models;

use CodeIgniter\Model;

class LogLoginModel extends Model
{
    protected $table            = 'log_login';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'role',
        'identifier',
        'ip_address',
        'logged_at',
    ];

    public function recordLogin(array $user, string $ipAddress): void
    {
        $role       = (string) ($user['role'] ?? 'pemohon');
        $identifier = !empty($user['nip']) ? (string) $user['nip'] : (string) ($user['email'] ?? '');

        $this->insert([
            'user_id'    => (int) ($user['id'] ?? 0) ?: null,
            'role'       => $role,
            'identifier' => $identifier,
            'ip_address' => $ipAddress ?: '127.0.0.1',
            'logged_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    public function getChartSeries(): array
    {
        $monthsShort = [
            1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
        ];

        $harianBuckets = [];
        for ($i = 6; $i >= 0; $i--) {
            $ts  = strtotime("-{$i} days");
            $key = date('Y-m-d', $ts);
            $harianBuckets[$key] = [
                'label'    => $i === 0 ? 'Hari Ini' : date('d', $ts) . ' ' . $monthsShort[(int) date('n', $ts)],
                'pemohon'  => 0,
                'aparatur' => 0,
                'total'    => 0,
            ];
        }

        $thisMondayTs    = strtotime('monday this week');
        $mingguanBuckets = [];
        for ($w = 3; $w >= 0; $w--) {
            $startTs = strtotime("-{$w} weeks", $thisMondayTs);
            $endTs   = strtotime('+6 days', $startTs);
            $key     = date('Y-m-d', $startTs);
            $label   = $w === 0
                ? 'Minggu Ini'
                : date('d', $startTs) . '–' . date('d', $endTs) . ' ' . $monthsShort[(int) date('n', $endTs)];
            $mingguanBuckets[$key] = [
                'label'    => $label,
                'start'    => $startTs,
                'end'      => strtotime('23:59:59', $endTs),
                'pemohon'  => 0,
                'aparatur' => 0,
                'total'    => 0,
            ];
        }

        $bulananBuckets = [];
        $firstDayCurMon = strtotime(date('Y-m-01'));
        for ($m = 5; $m >= 0; $m--) {
            $ts  = strtotime("-{$m} months", $firstDayCurMon);
            $key = date('Y-m', $ts);
            $bulananBuckets[$key] = [
                'label'    => $monthsShort[(int) date('n', $ts)] . ' ' . date('y', $ts),
                'pemohon'  => 0,
                'aparatur' => 0,
                'total'    => 0,
            ];
        }

        $oldestBoundary = min(
            date('Y-m-01 00:00:00', strtotime('-5 months', $firstDayCurMon)),
            date('Y-m-d 00:00:00', strtotime('-3 weeks', $thisMondayTs))
        );

        $rows = $this->select('role, logged_at')
            ->where('logged_at >=', $oldestBoundary)
            ->findAll();

        foreach ($rows as $row) {
            $ts = strtotime((string) $row['logged_at']);
            if ($ts === false) {
                continue;
            }

            $bucketRole = strtolower((string) $row['role']) === 'pemohon' ? 'pemohon' : 'aparatur';
            $dayKey     = date('Y-m-d', $ts);
            $monthKey   = date('Y-m', $ts);

            if (isset($harianBuckets[$dayKey])) {
                $harianBuckets[$dayKey][$bucketRole]++;
                $harianBuckets[$dayKey]['total']++;
            }

            foreach ($mingguanBuckets as $wkKey => $wk) {
                if ($ts >= $wk['start'] && $ts <= $wk['end']) {
                    $mingguanBuckets[$wkKey][$bucketRole]++;
                    $mingguanBuckets[$wkKey]['total']++;
                    break;
                }
            }

            if (isset($bulananBuckets[$monthKey])) {
                $bulananBuckets[$monthKey][$bucketRole]++;
                $bulananBuckets[$monthKey]['total']++;
            }
        }

        $cleanMingguan = array_map(static function (array $item): array {
            return [
                'label'    => $item['label'],
                'pemohon'  => $item['pemohon'],
                'aparatur' => $item['aparatur'],
                'total'    => $item['total'],
            ];
        }, array_values($mingguanBuckets));

        return [
            'harian'   => array_values($harianBuckets),
            'mingguan' => $cleanMingguan,
            'bulanan'  => array_values($bulananBuckets),
        ];
    }
}
