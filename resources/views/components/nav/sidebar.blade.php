<nav class="flex-1 px-2 pb-4 space-y-1">



    <x-nav.sidebar-link :link="route('dashboard')" label="Dashboard">
        <x-slot name="icon">
            <!-- Heroicon name: outline/home -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </x-slot>
    </x-nav.sidebar-link>

    <x-nav.sidebar-link :link="route('users')" label="Users">
        <x-slot name="icon">
            <!-- Heroicon name: outline/users -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </x-slot>
    </x-nav.sidebar-link>



    <!-- <x-nav.sidebar-link :link="route('teams.index')" label="Teams">
        <x-slot name="icon"> -->
            <!-- Heroicon name: outline/user-group -->
            <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </x-slot>
    </x-nav.sidebar-link>

    <x-nav.sidebar-link :link="route('carriers.index')" label="Carriers">
        <x-slot name="icon"> -->
            <!-- Heroicon name: outline/wifi -->
            <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
            </svg>
        </x-slot>
    </x-nav.sidebar-link>

    <x-nav.sidebar-link :link="route('simcards.index')" label="Simcards">
        <x-slot name="icon"> -->
            <!-- Heroicon name: outline/user-group -->
            <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
        </x-slot>
    </x-nav.sidebar-link> -->

    <div class="flex-col items-center text-sm font-medium" x-data="{ expanded: $persist(false).as('nbn.expanded') }">
        <x-nav.sidebar-link link="#" label="NBN" x-on:click="expanded = !expanded">
            <x-slot name="icon">
                <!-- Heroicon name: outline/location -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path d="M21.721 12.752a9.711 9.711 0 00-.945-5.003 12.754 12.754 0 01-4.339 2.708 18.991 18.991 0 01-.214 4.772 17.165 17.165 0 005.498-2.477zM14.634 15.55a17.324 17.324 0 00.332-4.647c-.952.227-1.945.347-2.966.347-1.021 0-2.014-.12-2.966-.347a17.515 17.515 0 00.332 4.647 17.385 17.385 0 005.268 0zM9.772 17.119a18.963 18.963 0 004.456 0A17.182 17.182 0 0112 21.724a17.18 17.18 0 01-2.228-4.605zM7.777 15.23a18.87 18.87 0 01-.214-4.774 12.753 12.753 0 01-4.34-2.708 9.711 9.711 0 00-.944 5.004 17.165 17.165 0 005.498 2.477zM21.356 14.752a9.765 9.765 0 01-7.478 6.817 18.64 18.64 0 001.988-4.718 18.627 18.627 0 005.49-2.098zM2.644 14.752c1.682.971 3.53 1.688 5.49 2.099a18.64 18.64 0 001.988 4.718 9.765 9.765 0 01-7.478-6.816zM13.878 2.43a9.755 9.755 0 016.116 3.986 11.267 11.267 0 01-3.746 2.504 18.63 18.63 0 00-2.37-6.49zM12 2.276a17.152 17.152 0 012.805 7.121c-.897.23-1.837.353-2.805.353-.968 0-1.908-.122-2.805-.353A17.151 17.151 0 0112 2.276zM10.122 2.43a18.629 18.629 0 00-2.37 6.49 11.266 11.266 0 01-3.746-2.504 9.754 9.754 0 016.116-3.985z" />
                  </svg>
            </x-slot>
            <div class="ml-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </x-nav.sidebar-link>
        <div class="pl-9" x-cloak x-show="expanded" x-collapse>
            {{-- <x-nav.sidebar-link :link="route('nbn.order.create')" label="Create SQs" /> --}}
            <div class="flex-col items-center text-sm font-medium" x-data="{ expanded: $persist(false).as('sqMenu.expanded') }">
                <x-nav.sidebar-link link="#" label="Service Qualifications" x-on:click="expanded = !expanded">
                    <x-slot name="icon">
                        <!-- Heroicon name: outline/location -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </x-slot>
                    <div class="ml-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </x-nav.sidebar-link>
                <div class="pl-9" x-cloak x-show="expanded" x-collapse>
                    {{-- <x-nav.sidebar-link :link="route('nbn.order.create')" label="Create SQs" /> --}}
                    <x-nav.sidebar-link :link="route('sq.index')" label="List SQs" />
                    <x-nav.sidebar-link :link="route('sq.search')" label="Location Search" />
                    <x-nav.sidebar-link :link="route('sq.qualify')" label="Service Qualification" />
                </div>
            </div>

            {{-- nbn 2 --}}
            <div class="flex-col items-center text-sm font-medium" x-data="{ expanded: $persist(false).as('ordersMenu.expanded') }">
                <x-nav.sidebar-link link="#" label="Orders" x-on:click="expanded = !expanded">
                    <x-slot name="icon">
                        <!-- Heroicon name: outline/document-add -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </x-slot>
                    <div class="ml-auto">
                        <!-- Heroicon name: outline chevron-down -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </x-nav.sidebar-link>
                <div class="pl-9" x-cloak x-show="expanded" x-collapse>
                    <x-nav.sidebar-link :link="route('qntrl.index')" label="Current Orders" />
                </div>
            </div>

            {{-- nbn 3 --}}

        </div>
    </div>


    <div class="flex-col items-center text-sm font-medium" x-data="{ expanded: $persist(false).as('mobileServices.expanded') }">
        <x-nav.sidebar-link link="#" label="Mobile Services" x-on:click="expanded = !expanded">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path d="M10.5 18.75a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" />
                    <path fill-rule="evenodd" d="M8.625.75A3.375 3.375 0 005.25 4.125v15.75a3.375 3.375 0 003.375 3.375h6.75a3.375 3.375 0 003.375-3.375V4.125A3.375 3.375 0 0015.375.75h-6.75zM7.5 4.125C7.5 3.504 8.004 3 8.625 3H9.75v.375c0 .621.504 1.125 1.125 1.125h2.25c.621 0 1.125-.504 1.125-1.125V3h1.125c.621 0 1.125.504 1.125 1.125v15.75c0 .621-.504 1.125-1.125 1.125h-6.75A1.125 1.125 0 017.5 19.875V4.125z" clip-rule="evenodd" />
                  </svg>

            </x-slot>
            <div class="ml-auto">
                <!-- Heroicon name: outline chevron-down -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </x-nav.sidebar-link>
        <div class="pl-9" x-cloak x-show="expanded" x-collapse>

        {{-- mobile services 1 --}}

            <div class="flex-col items-center text-sm font-medium" x-data="{ expanded: $persist(false).as('mobileplanes.expanded') }">
                <x-nav.sidebar-link link="#" label="Orders" x-on:click="expanded = !expanded">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                        </svg>
                    </x-slot>
                    <div class="ml-auto">
                        <!-- Heroicon name: outline chevron-down -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </x-nav.sidebar-link>
                <div class="pl-9" x-cloak x-show="expanded" x-collapse>
                    <x-nav.sidebar-link :link="route('orders.index', ['order_status' => 'new-order'])"  label="New Orders" />
                    <x-nav.sidebar-link :link="route('orders.index', ['order_status' => 'pending'])"  label="Pending Confirmation" />
                    <x-nav.sidebar-link :link="route('orders.index', ['order_status' => 'order-lodged'])"  label="Order Lodged" />
                    <x-nav.sidebar-link :link="route('orders.index', ['order_status' => 'completed'])"  label="Completed" />
                    <x-nav.sidebar-link :link="route('orders.index', ['order_status' => 'rejected'])"  label="Rejected" />
                    <x-nav.sidebar-link :link="route('orders.index', ['order_status' => 'cancelled'])"  label="Cancelled" />
                </div>
            </div>

            <x-nav.sidebar-link :link="route('services.index')" label="Services">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                      </svg>
                </x-slot>
            </x-nav.sidebar-link>

            <x-nav.sidebar-link :link="route('mobileplans.assign')" label="Create Order">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                      </svg>
                </x-slot>
            </x-nav.sidebar-link>

            {{-- mobile services 2 --}}
            <div class="flex-col items-center text-sm font-medium" x-data="{ expanded: $persist(false).as('dataPoolManagmentMenu.expanded') }">
                <x-nav.sidebar-link link="#" label="Data Pool Management" x-on:click="expanded = !expanded">
                    <x-slot name="icon">
                        <!-- Heroicon name: outline/circle-stack -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                        </svg>
                    </x-slot>
                    <div class="ml-auto">
                        <!-- Heroicon name: outline chevron-down -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </x-nav.sidebar-link>
                <div class="pl-9" x-cloak x-show="expanded" x-collapse>
                    <x-nav.sidebar-link :link="route('datapools.index')" label="Data Pools" />
                    <x-nav.sidebar-link :link="route('datapools.create')" label="Create New Pool" />
                </div>
            </div>

            {{-- mobile services 3 --}}

            <x-nav.sidebar-link :link="route('mobileplans.index')" label="Mobile Plans">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                      </svg>
                </x-slot>
            </x-nav.sidebar-link>

             {{-- mobile services 4 --}}
            <x-nav.sidebar-link :link="route('simcards.index')" label="SIM Cards">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z" />
                      </svg>
                </x-slot>
            </x-nav.sidebar-link>

             {{-- mobile services 5 --}}

             {{-- <div class="flex-col items-center text-sm font-medium" x-data="{ expanded: $persist(false).as('supports.expanded') }">
                <x-nav.sidebar-link link="#" label="Supports" x-on:click="expanded = !expanded">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                          </svg>
                    </x-slot>
                    <div class="ml-auto">
                        <!-- Heroicon name: outline chevron-down -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </x-nav.sidebar-link>
                <div class="pl-9" x-cloak x-show="expanded" x-collapse>
                    <x-nav.sidebar-link :link="route('supporttickets.index')" label="Support Tickets" />
                    <x-nav.sidebar-link :link="route('supporttickets.create')" label="Create Support Ticket" />
                </div>
            </div> --}}

            {{-- mobile services 6 --}}
            <div class="flex-col items-center text-sm font-medium" x-data="{ expanded: $persist(false).as('reports.expanded') }">
                <x-nav.sidebar-link link="#" label="Reports" x-on:click="expanded = !expanded">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                          </svg>


                    </x-slot>
                    <div class="ml-auto">
                        <!-- Heroicon name: outline chevron-down -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </x-nav.sidebar-link>
                <div class="pl-9" x-cloak x-show="expanded" x-collapse>
                    <x-nav.sidebar-link :link="route('reportgeneration')" label="Report Generation" />
                    {{-- <x-nav.sidebar-link :link="route('reportcustomization')" label="Report Customisation" /> --}}
                </div>
            </div>

            {{-- mobile services 7 --}}
            <div class="flex-col items-center text-sm font-medium" x-data="{ expanded: $persist(false).as('assets.expanded') }">
                <x-nav.sidebar-link link="#" label="In House Management" x-on:click="expanded = !expanded">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0118 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 004.5 9v.878m13.5-3A2.25 2.25 0 0119.5 9v.878m0 0a2.246 2.246 0 00-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0121 12v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6c0-.98.626-1.813 1.5-2.122" />
                          </svg>
                    </x-slot>
                    <div class="ml-auto">
                        <!-- Heroicon name: outline chevron-down -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </x-nav.sidebar-link>
                <div class="pl-9" x-cloak x-show="expanded" x-collapse>
                    <x-nav.sidebar-link :link="route('simcards.create')" label="Add SIM Cards" />
                    <x-nav.sidebar-link :link="route('simcards.index')" label="Manage SIM Cards" />
                    <x-nav.sidebar-link :link="route('mobileplans.create')" label="Add Mobile Plans" />
                    {{-- <x-nav.sidebar-link :link="route('mobileplans.assign')" label="Other Devices" /> --}}
                </div>
            </div>

        </div>
    </div>


{{--
    <div class="flex-col items-center text-sm font-medium" x-data="{ expanded: $persist(false).as('resellers.expanded') }">
        <x-nav.sidebar-link link="#" label="Reseller Accounts" x-on:click="expanded = !expanded">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                  </svg>
            </x-slot>
            <div class="ml-auto">
                <!-- Heroicon name: outline chevron-down -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </x-nav.sidebar-link>
        <div class="pl-9" x-cloak x-show="expanded" x-collapse>
            <x-nav.sidebar-link :link="route('resellers.index')" label="Resellers" />
            <x-nav.sidebar-link :link="route('resellers.create')" label="Add Reseller" />
        </div>
    </div> --}}

    {{-- <div class="flex-col items-center text-sm font-medium" x-data="{ expanded: $persist(false).as('assets.expanded') }">
        <x-nav.sidebar-link link="#" label="In House Management" x-on:click="expanded = !expanded">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0118 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 004.5 9v.878m13.5-3A2.25 2.25 0 0119.5 9v.878m0 0a2.246 2.246 0 00-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0121 12v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6c0-.98.626-1.813 1.5-2.122" />
                  </svg>

            </x-slot>
            <div class="ml-auto">
                <!-- Heroicon name: outline chevron-down -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </x-nav.sidebar-link>
        <div class="pl-9" x-cloak x-show="expanded" x-collapse> --}}

            {{-- inhouse management 1 --}}


            {{-- inhouse management 2 --}}





            {{--  inhouse management 3 --}}

            {{-- <div class="flex-col items-center text-sm font-medium" x-data="{ expanded: $persist(false).as('supports.expanded') }">
                <x-nav.sidebar-link link="#" label="Mobile Service order" x-on:click="expanded = !expanded">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                          </svg>
                    </x-slot>
                    <div class="ml-auto">
                        <!-- Heroicon name: outline chevron-down -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </x-nav.sidebar-link>
                <div class="pl-9" x-cloak x-show="expanded" x-collapse>
                    <x-nav.sidebar-link :link="route('orders.index')" label="Mobile Orders" />
                    <x-nav.sidebar-link :link="route('mobileplans.assign')" label="Porting" />
                </div>
            </div> --}}

            {{--  inhouse management 4 --}}
{{--
            <div class="flex-col items-center text-sm font-medium" x-data="{ expanded: $persist(false).as('admintasks.expanded') }">
                <x-nav.sidebar-link link="#" label="Administrative Tasks" x-on:click="expanded = !expanded">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                          </svg>

                    </x-slot>
                    <div class="ml-auto">
                        <!-- Heroicon name: outline chevron-down -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </x-nav.sidebar-link>
                <div class="pl-9" x-cloak x-show="expanded" x-collapse>
                    <x-nav.sidebar-link :link="route('orders.index')" label="Account Management" />
                    <x-nav.sidebar-link :link="route('mobileplans.index')" label="Existing Plans" />
                    <x-nav.sidebar-link :link="route('reportgeneration')" label="Report Generation" />
                    <x-nav.sidebar-link :link="route('reportcustomization')" label="Report Customisation" />
                </div>
            </div> --}}

            {{--  inhouse management 5 --}}


{{--
        </div>
    </div> --}}

    <div class="flex-col items-center text-sm font-medium" x-data="{ expanded: $persist(false).as('customers.expanded') }">
        <x-nav.sidebar-link link="#" label="Retail Customer Accounts" x-on:click="expanded = !expanded">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                  </svg>
            </x-slot>
            <div class="ml-auto">
                <!-- Heroicon name: outline chevron-down -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </x-nav.sidebar-link>
        <div class="pl-9" x-cloak x-show="expanded" x-collapse>
            <x-nav.sidebar-link :link="route('customers.index')" label="Retail Customers" />
            <x-nav.sidebar-link :link="route('customers.create')" label="Add Retail Customer" />
        </div>
    </div>


</nav>
