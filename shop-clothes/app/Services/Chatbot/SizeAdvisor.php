<?php

namespace App\Services\Chatbot;

/**
 * TU_VAN_SIZE
 * Đề xuất size dựa trên bảng size (config/chatbot.php):
 * - Xác định size theo chiều cao và theo cân nặng.
 * - Ưu tiên cân nặng (quyết định độ vừa của áo/quần); nếu chiều cao lệch từ 2 size trở lên thì tăng 1 size.
 * - Khi hai chỉ số lệch nhau, gợi ý thêm size thay thế (mặc rộng / mặc ôm).
 */
class SizeAdvisor
{
    public function __construct(private readonly array $chart)
    {
    }

    public static function fromConfig(): self
    {
        return new self(config('chatbot.size_chart'));
    }

    public function advise(?string $gender, ?int $heightCm, ?int $weightKg): ?array
    {
        if ($heightCm === null && $weightKg === null) {
            return null;
        }

        $assumed = !in_array($gender, ['nam', 'nu'], true);
        $gender = $assumed ? 'nam' : $gender;
        $rows = $this->chart[$gender];

        $byHeight = $heightCm !== null ? $this->indexFor($rows, 'height', $heightCm) : null;
        $byWeight = $weightKg !== null ? $this->indexFor($rows, 'weight', $weightKg) : null;

        if ($byHeight !== null && $byWeight !== null) {
            $index = $byWeight;
            if ($byHeight - $byWeight >= 2) {
                $index = min($byWeight + 1, count($rows) - 1);
            }
            $alt = $byHeight !== $index ? $byHeight : null;
        } else {
            $index = $byWeight ?? $byHeight;
            $alt = null;
        }

        $size = $rows[$index]['size'];
        $altSize = $alt !== null ? $rows[$alt]['size'] : null;

        $parts = [];
        if ($byHeight !== null) {
            $parts[] = 'chiều cao ' . $this->formatHeight($heightCm) . ' ứng với size **' . $rows[$byHeight]['size'] . '**';
        }
        if ($byWeight !== null) {
            $parts[] = 'cân nặng ' . $weightKg . 'kg ứng với size **' . $rows[$byWeight]['size'] . '**';
        }

        $explanation = 'Theo bảng size ' . ($gender === 'nu' ? 'nữ' : 'nam') . ' của shop: ' . implode(', ', $parts) . '.';
        if ($altSize) {
            $explanation .= $alt > $index
                ? " Shop ưu tiên theo cân nặng nên size phù hợp nhất là **{$size}**; nếu bạn thích mặc rộng rãi có thể chọn **{$altSize}**."
                : " Shop ưu tiên theo cân nặng nên size phù hợp nhất là **{$size}**; nếu bạn thích mặc ôm có thể chọn **{$altSize}**.";
        }

        return [
            'size' => $size,
            'alt_size' => $altSize,
            'gender' => $gender,
            'assumed_gender' => $assumed,
            'height_cm' => $heightCm,
            'weight_kg' => $weightKg,
            'explanation' => $explanation,
        ];
    }

    /** Bảng size dạng văn bản để hiển thị trong khung chat */
    public function chartText(?string $gender = null): string
    {
        $lines = [];
        foreach (['nam' => 'Nam', 'nu' => 'Nữ'] as $key => $label) {
            if ($gender && $gender !== $key) {
                continue;
            }
            $lines[] = "**Bảng size {$label}:**";
            foreach ($this->chart[$key] as $row) {
                $lines[] = sprintf(
                    '- %s: cao %s, nặng %s',
                    $row['size'],
                    $this->range($row['height'], 'cm'),
                    $this->range($row['weight'], 'kg')
                );
            }
        }

        return implode("\n", $lines);
    }

    private function indexFor(array $rows, string $field, int $value): int
    {
        foreach ($rows as $i => $row) {
            if ($value <= $row[$field][1]) {
                return $i;
            }
        }

        return count($rows) - 1;
    }

    private function range(array $r, string $unit): string
    {
        if ($r[0] === 0) {
            return 'dưới ' . ($r[1] + 1) . $unit;
        }
        if ($r[1] >= 200) {
            return 'từ ' . $r[0] . $unit;
        }

        return $r[0] . '–' . $r[1] . $unit;
    }

    private function formatHeight(int $cm): string
    {
        return intdiv($cm, 100) . 'm' . str_pad((string) ($cm % 100), 2, '0', STR_PAD_LEFT);
    }
}
