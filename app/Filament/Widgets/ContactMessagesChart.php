<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use Filament\Widgets\ChartWidget;

class ContactMessagesChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Enquiries (last 14 days)';

    protected ?string $description = 'Contact form submissions per day.';

    protected ?string $maxHeight = '260px';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $days = collect(range(13, 0))->map(fn (int $daysAgo) => now()->subDays($daysAgo)->startOfDay());

        $counts = $days->map(fn ($day): int => ContactMessage::query()
            ->whereDate('created_at', $day->toDateString())
            ->count());

        return [
            'datasets' => [
                [
                    'label' => 'Enquiries',
                    'data' => $counts->all(),
                    'fill' => true,
                ],
            ],
            'labels' => $days->map(fn ($day) => $day->format('d M'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
