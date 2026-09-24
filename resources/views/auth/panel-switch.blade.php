<div class="mt-6 mb-6 flex flex-col items-center">

    <!-- Label -->
    <div class="text-xs font-semibold tracking-wider text-gray-400 mb-3 uppercase">
        Login as
    </div>

    <!-- Container -->
    <div class="flex bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl p-1 shadow-lg space-x-1">

        <!-- Admin -->
        <a href="{{ url('/admin/login') }}"
           class="relative px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300
           {{ request()->is('admin/*')
                ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-md scale-105'
                : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
            Admin
        </a>

        <!-- HR -->
        <a href="{{ url('/hr/login') }}"
           class="relative px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300
           {{ request()->is('hr/*')
                ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-md scale-105'
                : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
            HR
        </a>

        <!-- Employee -->
        <a href="{{ url('/employee/login') }}"
           class="relative px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300
           {{ request()->is('employee/*')
                ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-md scale-105'
                : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
            Employee
        </a>

    </div>
</div>