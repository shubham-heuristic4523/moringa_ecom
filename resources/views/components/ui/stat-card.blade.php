{{--
 | Component: components/ui/stat-card.blade.php
 | Purpose  : Admin dashboard KPI metric card.
 | Usage    : <x-ui.stat-card label="Today's Sales" value="$45.2k" trend="+12%" trendUp icon="attach_money" />
--}}
@props([
    'label'   => 'Metric',
    'value'   => '0',
    'trend'   => null,
    'trendUp' => true,
    'icon'    => 'bar_chart',
])

<div class="bg-surface border border-outline-variant/50 rounded-xl p-xl shadow-sm hover:shadow-md transition-shadow">
    <div class="flex justify-between items-start mb-md">
        <span class="font-body-sm text-body-sm text-on-surface-variant">{{ $label }}</span>
        <span class="material-symbols-outlined text-primary bg-primary-container/20 p-sm rounded-lg">{{ $icon }}</span>
    </div>
    <div class="font-h4 text-h4 text-on-surface mb-xs">{{ $value }}</div>
    @if($trend)
        <div class="font-caption text-caption {{ $trendUp ? 'text-secondary' : 'text-error' }} flex items-center gap-xs">
            <span class="material-symbols-outlined text-[16px]">{{ $trendUp ? 'trending_up' : 'trending_down' }}</span>
            {{ $trend }}
        </div>
    @endif
</div>
