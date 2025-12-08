<nav x-data="{ open: false }" class="bg-blue-900 border-b border-blue-800 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 transition hover:opacity-80">
                        <div class="bg-white p-2 rounded-full shadow-sm">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-white font-bold text-lg leading-tight">หน่วยงานบริการร้องทุกข์ออนไลน์</span>
                            <span class="text-yellow-400 text-sm font-medium">BY นายก บรรยงค์</span>
                        </div>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    
                    @if(auth()->user()->role === 'complainant' || auth()->user()->role === 'council_member')
                        <x-nav-link :href="route('complaints.create')" :active="request()->routeIs('complaints.create')" class="text-white hover:text-yellow-400 hover:border-yellow-400 focus:text-yellow-400 focus:border-yellow-400">
                            {{ __('เขียนคำร้อง') }}
                        </x-nav-link>
                        <x-nav-link :href="route('complaints.history')" :active="request()->routeIs('complaints.history')" class="text-white hover:text-yellow-400 hover:border-yellow-400 focus:text-yellow-400 focus:border-yellow-400">
                            {{ __('ติดตามสถานะ') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.complaints.index')" :active="request()->routeIs('admin.complaints.index')" class="text-yellow-300 hover:text-yellow-100 hover:border-yellow-100 font-bold">
                            {{ __('Admin Panel') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')" class="text-yellow-300 hover:text-yellow-100 hover:border-yellow-100 font-bold">
                            {{ __('จัดการสมาชิก (สท.)') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.news.index')" :active="request()->routeIs('admin.news.index')" class="text-yellow-300 hover:text-yellow-100 hover:border-yellow-100 font-bold">
                            {{ __('จัดการข่าวสาร') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.banners.index')" :active="request()->routeIs('admin.banners.index')" class="text-yellow-300 hover:text-yellow-100 hover:border-yellow-100 font-bold">
                            {{ __('จัดการแบนเนอร์') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.index')" class="text-yellow-300 hover:text-yellow-100 hover:border-yellow-100 font-bold">
                            {{ __('ตั้งค่า Footer') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-800 hover:bg-blue-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center gap-2">
                                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                @if(Auth::user()->role === 'council_member')
                                    <span class="bg-yellow-400 text-blue-900 text-xs px-2 py-0.5 rounded-full font-bold shadow-sm">
                                        สท. {{ Auth::user()->zone }}
                                    </span>
                                @endif
                            </div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('ข้อมูลส่วนตัว') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('ออกจากระบบ') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-blue-200 hover:text-white hover:bg-blue-800 focus:outline-none focus:bg-blue-800 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-blue-800">
        <div class="pt-2 pb-3 space-y-1">
            
            @if(auth()->user()->role === 'complainant' || auth()->user()->role === 'council_member')
                <x-responsive-nav-link :href="route('complaints.create')" :active="request()->routeIs('complaints.create')" class="text-white">
                    {{ __('เขียนคำร้อง') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('complaints.history')" :active="request()->routeIs('complaints.history')" class="text-white">
                    {{ __('ติดตามสถานะ') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.complaints.index')" :active="request()->routeIs('admin.complaints.index')" class="text-yellow-300 font-bold">
                    {{ __('Admin Panel') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')" class="text-yellow-300 font-bold">
                    {{ __('จัดการสมาชิก (สท.)') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.news.index')" :active="request()->routeIs('admin.news.index')" class="text-yellow-300 font-bold">
                    {{ __('จัดการข่าวสาร') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.banners.index')" :active="request()->routeIs('admin.banners.index')" class="text-yellow-300 font-bold">
                    {{ __('จัดการแบนเนอร์') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.index')" class="text-yellow-300 font-bold">
                    {{ __('ตั้งค่า Footer') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-blue-700">
            <div class="px-4">
                <div class="font-medium text-base text-white flex items-center gap-2">
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    @if(Auth::user()->role === 'council_member')
                        <span class="bg-yellow-400 text-blue-900 text-xs px-2 py-0.5 rounded-full font-bold">
                            สท. {{ Auth::user()->zone }}
                        </span>
                    @endif
                </div>
                <div class="font-medium text-sm text-blue-200">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white">
                    {{ __('ข้อมูลส่วนตัว') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" class="text-white"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('ออกจากระบบ') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>