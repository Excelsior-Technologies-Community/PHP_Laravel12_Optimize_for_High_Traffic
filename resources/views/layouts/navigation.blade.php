<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- LEFT SECTION --}}
            <div class="flex items-center gap-8">
                {{-- Brand Logo / Name --}}
                <a href="{{ route('products.index') }}" class="flex items-center gap-2 text-xl font-extrabold tracking-tight text-gray-900 group">
                    <span class="bg-indigo-600 text-white p-1.5 rounded-lg shadow-sm group-hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </span>
                    <span class="hover:text-indigo-600 transition-colors">AdminPanel</span>
                </a>

                {{-- DESKTOP NAV (No Hidden Scrollbars, Flex Items are Wrapped Properly) --}}
                <div class="hidden lg:flex lg:items-center lg:gap-2 flex-wrap">

                    {{-- Products Dropdown --}}
                    <div class="relative" x-data="{ show: false }" @click.away="show = false">
                        <button @mouseenter="show=true" @click="show = !show" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-gray-600 hover:text-indigo-600 rounded-xl hover:bg-gray-50 transition-all duration-200">
                            <span>Products</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180 text-indigo-600': show }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="show"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 transform"
                            x-transition:enter-end="opacity-100 scale-100 transform"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 transform"
                            x-transition:leave-end="opacity-0 scale-95 transform"
                            @mouseleave="show=false"
                            class="absolute left-0 top-full mt-1 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-1.5 backdrop-blur-md">
                            <a href="{{ route('products.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg mx-1 transition-colors">All Products</a>
                            <a href="{{ route('products.create') }}" class="flex items-center px-4 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg mx-1 transition-colors">+ Add Product</a>
                            <div class="border-t border-gray-100 my-1.5"></div>
                            <a href="{{ route('sizes.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg mx-1 transition-colors">Sizes</a>
                            <a href="{{ route('colors.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg mx-1 transition-colors">Colors</a>
                            <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg mx-1 transition-colors">Categories</a>
                            <a href="{{ route('brands.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg mx-1 transition-colors">Brands</a>
                            <a href="{{ route('product-tags.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg mx-1 transition-colors">Tags</a>
                            <a href="{{ route('product-variants.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg mx-1 transition-colors">Variants</a>
                            <a href="{{ route('size-guides.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg mx-1 transition-colors">Size Guides</a>
                        </div>
                    </div>

                    {{-- Sales Dropdown --}}
                    <div class="relative" x-data="{ show: false }" @click.away="show = false">
                        <button @mouseenter="show=true" @click="show = !show" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-gray-600 hover:text-indigo-600 rounded-xl hover:bg-gray-50 transition-all duration-200">
                            <span>Sales</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180 text-indigo-600': show }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="show"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 transform"
                            x-transition:enter-end="opacity-100 scale-100 transform"
                            @mouseleave="show=false"
                            class="absolute left-0 top-full mt-1 w-52 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-1.5">
                            <a href="{{ route('discounts.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg mx-1 transition-colors">Discounts</a>
                            <a href="{{ route('flash-sales.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg mx-1 transition-colors">Flash Sales</a>
                            <a href="{{ route('admin.gift-cards.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg mx-1 transition-colors">Gift Cards</a>
                        </div>
                    </div>

                    {{-- Orders Dropdown --}}
                    <div class="relative" x-data="{ show: false }" @click.away="show = false">
                        <button @mouseenter="show=true" @click="show = !show" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-gray-600 hover:text-indigo-600 rounded-xl hover:bg-gray-50 transition-all duration-200">
                            <span>Orders</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180 text-indigo-600': show }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="show"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 transform"
                            x-transition:enter-end="opacity-100 scale-100 transform"
                            @mouseleave="show=false"
                            class="absolute left-0 top-full mt-1 w-52 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-1.5">
                            <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg mx-1 transition-colors">All Orders</a>
                            <a href="{{ route('admin.returns.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg mx-1 transition-colors">Returns</a>
                            <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg mx-1 transition-colors">Reviews</a>
                        </div>
                    </div>

                    {{-- Customers Dropdown --}}
                    <div class="relative" x-data="{ show: false }" @click.away="show = false">
                        <button @mouseenter="show=true" @click="show = !show" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-gray-600 hover:text-indigo-600 rounded-xl hover:bg-gray-50 transition-all duration-200">
                            <span>Customers</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180 text-indigo-600': show }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="show"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 transform"
                            x-transition:enter-end="opacity-100 scale-100 transform"
                            @mouseleave="show=false"
                            class="absolute left-0 top-full mt-1 w-52 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-1.5">
                            <a href="{{ route('admin.wallets.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg mx-1 transition-colors">Wallets</a>
                            <a href="{{ route('import-export.form') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg mx-1 transition-colors">Import/Export</a>
                        </div>
                    </div>

                    {{-- Banners Link --}}
                    <a href="{{ route('banners.index') }}" class="inline-flex items-center px-3.5 py-2 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('banners.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        Banners
                    </a>

                    {{-- Traffic Monitoring --}}
                    <a href="{{ route('traffic.dashboard') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('traffic.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">

                        <svg class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3v18h18M7 16l4-5 3 3 5-7" />

                        </svg>

                        Traffic Monitoring

                    </a>

                    {{-- Customer Side Dropdown --}}
                    <div class="relative" x-data="{ show: false }" @click.away="show = false">
                        <button @mouseenter="show=true" @click="show = !show" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-gray-600 hover:text-green-600 rounded-xl hover:bg-green-50 transition-all duration-200">
                            <span>Customer Side</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180 text-green-600': show }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="show"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 transform"
                            x-transition:enter-end="opacity-100 scale-100 transform"
                            @mouseleave="show=false"
                            class="absolute left-0 top-full mt-1 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-1.5">
                            <a href="{{ route('customer.products') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mx-1 transition-colors" target="_blank">Products Catalog</a>
                            <a href="{{ route('cart.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mx-1 transition-colors" target="_blank">Cart</a>
                            <a href="{{ route('wishlist.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mx-1 transition-colors" target="_blank">Wishlist</a>
                            <a href="{{ route('compare.show') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mx-1 transition-colors" target="_blank">Compare</a>
                            <a href="{{ route('gift-cards.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mx-1 transition-colors" target="_blank">Gift Cards</a>
                            <a href="{{ route('customer.orders') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mx-1 transition-colors" target="_blank">My Orders</a>
                            <a href="{{ route('wallet.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mx-1 transition-colors" target="_blank">Wallet</a>
                            <a href="{{ route('customer.profile') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg mx-1 transition-colors" target="_blank">Profile</a>
                        </div>
                    </div>

                </div>
            </div>

            {{-- RIGHT SECTION --}}
            <div class="hidden lg:flex lg:items-center lg:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-3 py-1.5 text-sm font-semibold rounded-xl text-gray-700 hover:bg-gray-50 border border-gray-200/60 shadow-sm transition-all focus:outline-none">
                            <div class="w-7 h-7 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold text-xs uppercase shadow-inner">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-2.5 border-b border-gray-100">
                            <p class="text-xs text-gray-400 font-medium">Logged in as</p>
                            <p class="text-sm font-bold text-gray-700 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <x-dropdown-link :href="route('admin.dashboard')" class="font-medium">Dashboard</x-dropdown-link>
                        <div class="border-t border-gray-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 font-semibold hover:bg-red-50">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- MOBILE HAMBURGER BUTTON --}}
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2.5 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-indigo-600 focus:outline-none transition-all">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden lg:hidden bg-gray-50/50 border-t border-gray-100 max-h-[calc(100vh-4rem)] overflow-y-auto transition-all">
        <div class="pt-2 pb-4 space-y-1 px-3">
            <div class="px-3 py-1 text-xs font-bold text-indigo-500 uppercase tracking-wider">Products Management</div>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="rounded-xl">All Products</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.create')" class="rounded-xl text-indigo-600 font-semibold">+ Add Product</x-responsive-nav-link>

            <div class="grid grid-cols-2 gap-1 py-1 pl-3">
                <a href="{{ route('sizes.index') }}" class="text-sm py-2 px-3 text-gray-600 hover:bg-white rounded-lg">Sizes</a>
                <a href="{{ route('colors.index') }}" class="text-sm py-2 px-3 text-gray-600 hover:bg-white rounded-lg">Colors</a>
                <a href="{{ route('categories.index') }}" class="text-sm py-2 px-3 text-gray-600 hover:bg-white rounded-lg">Categories</a>
                <a href="{{ route('brands.index') }}" class="text-sm py-2 px-3 text-gray-600 hover:bg-white rounded-lg">Brands</a>
                <a href="{{ route('product-tags.index') }}" class="text-sm py-2 px-3 text-gray-600 hover:bg-white rounded-lg">Tags</a>
                <a href="{{ route('product-variants.index') }}" class="text-sm py-2 px-3 text-gray-600 hover:bg-white rounded-lg">Variants</a>
            </div>

            <div class="border-t border-gray-200/60 my-2"></div>
            <div class="px-3 py-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Sales & Orders</div>
            <x-responsive-nav-link :href="route('discounts.index')" :active="request()->routeIs('discounts.*')" class="rounded-xl">Discounts</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('flash-sales.index')" :active="request()->routeIs('flash-sales.*')" class="rounded-xl">Flash Sales</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.gift-cards.index')" :active="request()->routeIs('admin.gift-cards.*')" class="rounded-xl">Gift Cards</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" class="rounded-xl">Orders</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.returns.index')" :active="request()->routeIs('admin.returns.*')" class="rounded-xl">Returns</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')" class="rounded-xl">Reviews</x-responsive-nav-link>

            <div class="border-t border-gray-200/60 my-2"></div>
            <div class="px-3 py-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Customer Side</div>
            <x-responsive-nav-link :href="route('customer.products')" :active="request()->routeIs('customer.products.*')" class="rounded-xl" target="_blank">Products Catalog</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')" class="rounded-xl" target="_blank">Cart</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('wishlist.index')" class="rounded-xl" target="_blank">Wishlist</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('compare.show')" class="rounded-xl" target="_blank">Compare</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('gift-cards.index')" :active="request()->routeIs('gift-cards.*')" class="rounded-xl" target="_blank">Gift Cards</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customer.orders')" class="rounded-xl" target="_blank">My Orders</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('wallet.index')" class="rounded-xl" target="_blank">Wallet</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customer.profile')" class="rounded-xl" target="_blank">Profile</x-responsive-nav-link>

            <div class="border-t border-gray-200/60 my-2"></div>
            <div class="px-3 py-1 text-xs font-bold text-gray-400 uppercase tracking-wider">System</div>
            <x-responsive-nav-link :href="route('admin.wallets.index')" :active="request()->routeIs('admin.wallets.*')" class="rounded-xl">Wallets</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('banners.index')" :active="request()->routeIs('banners.*')" class="rounded-xl">Banners</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('import-export.form')" :active="request()->routeIs('import-export.*')" class="rounded-xl">Import/Export</x-responsive-nav-link>
            <x-responsive-nav-link
                :href="route('traffic.dashboard')"
                :active="request()->routeIs('traffic.*')"
                class="rounded-xl">

                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3v18h18M7 16l4-5 3 3 5-7" />

                    </svg>

                    Traffic Monitoring
                </span>

            </x-responsive-nav-link>
        </div>

        {{-- Mobile User Info Section --}}
        <div class="pt-4 pb-3 border-t border-gray-200 bg-gray-50 px-4 rounded-b-xl">
            <div class="flex items-center gap-3 px-2">
                <div class="w-9 h-9 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <div>
                    <div class="font-bold text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 font-bold bg-red-50/50 rounded-xl mx-2">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>