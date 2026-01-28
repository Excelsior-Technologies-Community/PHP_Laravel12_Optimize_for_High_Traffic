<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- LEFT --}}
            <div class="flex items-center">
                {{-- LOGO --}}
                <a href="{{ route('products.index') }}"
                   class="text-lg font-bold text-gray-800">
                     Admin Panel
                </a>

                {{-- LINKS (DESKTOP) --}}
                <div class="hidden sm:flex sm:space-x-8 sm:ms-10">
                    <x-nav-link
        :href="route('products.index')"
        :active="request()->routeIs('products.*')">
        Products
    </x-nav-link>

    <x-nav-link
        :href="route('sizes.index')"
        :active="request()->routeIs('sizes.*')">
        Sizes
    </x-nav-link>

    <x-nav-link
        :href="route('colors.index')"
        :active="request()->routeIs('colors.*')">
        Colors
    </x-nav-link>

    <x-nav-link
        :href="route('categories.index')"
        :active="request()->routeIs('categories.*')">
        Categories
    </x-nav-link>

    <x-nav-link
        :href="route('discounts.index')"
        :active="request()->routeIs('discounts.*')">
        Discounts
    </x-nav-link>

    <x-nav-link
        :href="route('admin.orders.index')"
        :active="request()->routeIs('admin.orders.*')">
        ALLOrders
    </x-nav-link>

    {{-- 🔵 ADD PRODUCT BUTTON STYLE --}}
    <a href="{{ route('products.create') }}"
       class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
        + Add Product
    </a>

                    
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:text-gray-800 focus:outline-none">
                            <span>{{ Auth::user()->name }}</span>

                            <svg class="h-4 w-4 fill-current"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-sm text-gray-500">
                            {{ Auth::user()->email }}
                        </div>

                       <x-dropdown-link :href="route('admin.dashboard')">
    Dashboard
</x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-red-600">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- HAMBURGER --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                              class="inline-flex"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link
                :href="route('products.index')"
                :active="request()->routeIs('products.*')">
                Products
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('admin.orders.index')"
                :active="request()->routeIs('admin.orders.*')">
                Orders
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('discounts.index')"
                :active="request()->routeIs('discounts.*')">
                Discounts
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">
                    {{ Auth::user()->name }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link
                        :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="text-red-600">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
