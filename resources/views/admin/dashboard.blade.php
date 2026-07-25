{{--
 | Page: admin/dashboard.blade.php
 | Layout: layouts/admin
 | Source: admin_executive_dashboard_overview/code.html
--}}
@extends('layouts.admin')

@php
    $title       = 'Dashboard Overview - LUXE Admin';
    $activeItem  = 'dashboard';
    $breadcrumbs = [
        ['label' => 'Home', 'route' => 'admin.dashboard'],
        ['label' => 'Dashboard'],
    ];
@endphp

@section('content')

<h1 class="font-h3 text-h3 text-on-surface">Dashboard Overview</h1>

{{-- ── Key Metrics Bento ──────────────────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2xl">

    <x-ui.stat-card
        label="Today's Sales"
        :value="money($metrics->todaySales ?? 45200)"
        trend="+12% from yesterday"
        :trendUp="true"
        icon="attach_money"
    />
    <x-ui.stat-card
        label="Orders"
        :value="number_format($metrics->todayOrders ?? 1240)"
        trend="+5% from yesterday"
        :trendUp="true"
        icon="shopping_bag"
    />
    <x-ui.stat-card
        label="New Customers"
        :value="number_format($metrics->newCustomers ?? 856)"
        trend="+8% from yesterday"
        :trendUp="true"
        icon="person_add"
    />
    <x-ui.stat-card
        label="Conversion Rate"
        :value="($metrics->conversionRate ?? 3.4).'%'"
        trend="-1% from yesterday"
        :trendUp="false"
        icon="show_chart"
    />

</div>

{{-- ── Main Grid ──────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-3xl">

    {{-- Chart & Table Column --}}
    <div class="lg:col-span-2 flex flex-col gap-3xl">

        {{-- Revenue Chart Card --}}
        <div class="bg-surface border border-outline-variant/50 rounded-xl p-2xl shadow-sm">
            <div class="flex justify-between items-center mb-2xl">
                <h3 class="font-h6 text-h6 text-on-surface">Revenue Trend</h3>
                <select class="bg-surface border border-outline-variant rounded-md font-body-sm text-body-sm py-sm px-md text-on-surface-variant focus:border-primary focus:ring-1 focus:ring-primary/10"
                        aria-label="Select time range">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                    <option>This Year</option>
                </select>
            </div>

            {{-- Chart Bars (CSS-based decorative; replace with Chart.js in production) --}}
            <div class="h-64 w-full bg-surface-container-lowest rounded-lg border border-outline-variant/30 flex items-end px-md pb-md gap-sm relative overflow-hidden"
                 aria-label="Revenue chart" role="img">
                @foreach($chartData ?? [40, 60, 35, 80, 95, 75, 50] as $height)
                    <div class="w-full bg-primary/{{ round($height/100 * 5) * 10 }} hover:bg-primary/{{ min(100, round($height/100 * 5) * 10 + 20) }} transition-colors rounded-t-sm"
                         style="height: {{ $height }}%">
                    </div>
                @endforeach
                <div class="absolute inset-0 bg-gradient-to-t from-surface/50 to-transparent pointer-events-none"></div>
            </div>
        </div>

        {{-- Recent Orders Table --}}
        <div class="bg-surface border border-outline-variant/50 rounded-xl p-2xl shadow-sm overflow-hidden">
            <div class="flex justify-between items-center mb-xl">
                <h3 class="font-h6 text-h6 text-on-surface">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="font-button text-button text-primary hover:underline">View All</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-outline-variant/30 text-on-surface-variant font-caption text-caption uppercase tracking-wider">
                            <th class="pb-md font-medium">Order ID</th>
                            <th class="pb-md font-medium">Customer</th>
                            <th class="pb-md font-medium">Date</th>
                            <th class="pb-md font-medium">Amount</th>
                            <th class="pb-md font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="font-body-sm text-body-sm">
                        @forelse($recentOrders ?? [] as $order)
                            <tr class="border-b border-outline-variant/10 hover:bg-surface-container-lowest transition-colors">
                                <td class="py-md font-medium text-on-surface">#{{ $order->order_number }}</td>
                                <td class="py-md">{{ $order->customer->name }}</td>
                                <td class="py-md text-on-surface-variant">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="py-md font-medium">{{ money($order->total) }}</td>
                                <td class="py-md">
                                    <x-ui.badge :variant="$order->status">{{ ucfirst($order->status) }}</x-ui.badge>
                                </td>
                            </tr>
                        @empty
                            {{-- Demo rows when no data --}}
                            <tr class="border-b border-outline-variant/10 hover:bg-surface-container-lowest transition-colors">
                                <td class="py-md font-medium text-on-surface">#ORD-9021</td>
                                <td class="py-md">Sarah Jenkins</td>
                                <td class="py-md text-on-surface-variant">Oct 24, 2024</td>
                                <td class="py-md font-medium">$345.00</td>
                                <td class="py-md">
                                    <x-ui.badge variant="pending">Pending</x-ui.badge>
                                </td>
                            </tr>
                            <tr class="border-b border-outline-variant/10 hover:bg-surface-container-lowest transition-colors">
                                <td class="py-md font-medium text-on-surface">#ORD-9020</td>
                                <td class="py-md">Michael Chen</td>
                                <td class="py-md text-on-surface-variant">Oct 24, 2024</td>
                                <td class="py-md font-medium">$1,290.50</td>
                                <td class="py-md">
                                    <x-ui.badge variant="delivered">Delivered</x-ui.badge>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="py-md font-medium text-on-surface">#ORD-9019</td>
                                <td class="py-md">Emma Wilson</td>
                                <td class="py-md text-on-surface-variant">Oct 23, 2024</td>
                                <td class="py-md font-medium">$85.25</td>
                                <td class="py-md">
                                    <x-ui.badge variant="shipped">Shipped</x-ui.badge>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Right Column: Alerts & Lists --}}
    <div class="flex flex-col gap-3xl">

        {{-- Low Stock Alerts --}}
        <div class="bg-surface border border-outline-variant/50 rounded-xl p-2xl shadow-sm">
            <h3 class="font-h6 text-h6 text-on-surface mb-xl flex items-center gap-sm">
                <span class="material-symbols-outlined text-primary">warning</span>
                Low Stock Alerts
            </h3>
            <div class="flex flex-col gap-md">
                @forelse($lowStockProducts ?? [] as $product)
                    <div class="flex items-center justify-between p-md {{ $product->stock_qty <= 5 ? 'bg-error-container/20 border-error-container/50' : 'bg-surface-container-lowest border-outline-variant/30 hover:border-outline-variant' }} rounded-lg border">
                        <div class="flex items-center gap-md">
                            <div class="w-10 h-10 rounded-md bg-surface-container-highest overflow-hidden">
                                <img class="w-full h-full object-cover" src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy"/>
                            </div>
                            <div>
                                <p class="font-body-sm text-body-sm font-medium text-on-surface">{{ $product->name }}</p>
                                <p class="font-caption text-caption {{ $product->stock_qty <= 5 ? 'text-error' : 'text-on-surface-variant' }}">
                                    Only {{ $product->stock_qty }} left
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.products.edit', $product->id) }}"
                           class="text-primary font-button text-button hover:underline">Restock</a>
                    </div>
                @empty
                    {{-- Demo items --}}
                    <div class="flex items-center justify-between p-md bg-error-container/20 rounded-lg border border-error-container/50">
                        <div class="flex items-center gap-md">
                            <div class="w-10 h-10 rounded-md bg-surface-container-highest overflow-hidden">
                                <div class="w-full h-full bg-surface-container animate-pulse"></div>
                            </div>
                            <div>
                                <p class="font-body-sm text-body-sm font-medium text-on-surface">Apex Smartwatch</p>
                                <p class="font-caption text-caption text-error">Only 4 left</p>
                            </div>
                        </div>
                        <button class="text-primary font-button text-button hover:underline">Restock</button>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Top Selling Products --}}
        <div class="bg-surface border border-outline-variant/50 rounded-xl p-2xl shadow-sm flex-1">
            <h3 class="font-h6 text-h6 text-on-surface mb-xl">Top Selling Products</h3>
            <div class="flex flex-col gap-lg">
                @forelse($topProducts ?? [] as $index => $product)
                    <div class="flex items-center gap-md group">
                        <div class="font-h5 text-h5 text-outline-variant w-6 text-center">{{ $index + 1 }}</div>
                        <div class="w-12 h-12 rounded-lg bg-surface-container-highest overflow-hidden">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy"/>
                        </div>
                        <div class="flex-1">
                            <p class="font-body-sm text-body-sm font-medium text-on-surface">{{ $product->name }}</p>
                            <p class="font-caption text-caption text-on-surface-variant">{{ $product->weekly_sales }} sales this week</p>
                        </div>
                        <div class="font-body-sm text-body-sm font-medium text-on-surface">{{ money($product->revenue) }}</div>
                    </div>
                @empty
                    {{-- Demo items --}}
                    @foreach([['ErgoPro Chair','245 sales this week','$245k'],['UltraView Monitor','189 sales this week','$168k'],['Mechanical Keyset C','156 sales this week','$23k']] as $idx => $row)
                        <div class="flex items-center gap-md group">
                            <div class="font-h5 text-h5 text-outline-variant w-6 text-center">{{ $idx+1 }}</div>
                            <div class="w-12 h-12 rounded-lg bg-surface-container-highest overflow-hidden">
                                <div class="w-full h-full bg-surface-container animate-pulse"></div>
                            </div>
                            <div class="flex-1">
                                <p class="font-body-sm text-body-sm font-medium text-on-surface">{{ $row[0] }}</p>
                                <p class="font-caption text-caption text-on-surface-variant">{{ $row[1] }}</p>
                            </div>
                            <div class="font-body-sm text-body-sm font-medium text-on-surface">{{ $row[2] }}</div>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>

    </div>

</div>

@endsection
