<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            My Profile
        </x-slot>

        <div class="space-y-6">

            {{-- PERSONAL INFORMATION --}}
            <x-filament::card>
                <div class="fi-section-content-ctn">
                    <div class="fi-section-content">
                        <h3 class="text-base font-semibold mb-4">Personal Information</h3>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                            @php
                                $personal = [
                                    'Name' => $user->name,
                                    'Company Email' => $user->email,
                                    'Alias Name' => $user->alias_name,
                                    'Personal Email' => $user->company_alias_email,
                                    'Phone' => $user->phone,
                                    'Date of Birth' => optional($user->date_of_birth)->format('d M Y'),
                                ];
                            @endphp

                            @foreach ($personal as $label => $value)
                                <x-filament::card>
                                    <div style="text-align: center; padding: 1rem;">
                                        <p style="font-size: 0.75rem; font-weight: 500; color: rgb(107 114 128); margin-bottom: 0.25rem;">
                                            {{ $label }}
                                        </p>
                                        <p style="
                                                    font-size: 1rem;
                                                    font-weight: 600;
                                                    word-break: break-all;
                                                    overflow-wrap: anywhere;
                                                    line-height: 1.4;
                                             ">
                                            {{ $value ?: '—' }}
                                        </p>
                                    </div>
                                </x-filament::card>
                            @endforeach

                            <x-filament::card>
                                <div style="text-align: center; padding: 1rem;">
                                    <p style="font-size: 0.75rem; font-weight: 500; color: rgb(107 114 128); margin-bottom: 0.25rem;">
                                        Address
                                    </p>
                                    <p style="
    font-size: 1rem;
    font-weight: 600;
    word-break: break-all;
    overflow-wrap: anywhere;
    line-height: 1.4;
">
   {{ $user->address ?: '—' }}

                                    </p>
                                </div>
                            </x-filament::card>
                        </div>
                    </div>
                </div>
            </x-filament::card>

            {{-- EMPLOYMENT DETAILS --}}
            <x-filament::card>
                <div class="fi-section-content-ctn">
                    <div class="fi-section-content">
                        <h3 class="text-base font-semibold mb-4">Employment Details</h3>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                            @php
                                $employment = [
                                    'Employee Code' => $user->employee_id,
                                    'Department' => $user->department?->name,
                                    'Position' => $user->position?->title,
                                    'Hire Date' => optional($user->hire_date)->format('d M Y'),
                                    'Employment Type' => ucfirst($user->employment_type ?? ''),
                                    'Salary' => '₹ ' . number_format($user->salary ?? 0, 2),
                                ];
                            @endphp

                            @foreach ($employment as $label => $value)
                                <x-filament::card>
                                    <div style="text-align: center; padding: 1rem;">
                                        <p style="font-size: 0.75rem; font-weight: 500; color: rgb(107 114 128); margin-bottom: 0.25rem;">
                                            {{ $label }}
                                        </p>
                                        <p style="font-size: 1rem; font-weight: 600;">
                                            {{ trim($value) !== '' ? $value : '—' }}
                                        </p>
                                    </div>
                                </x-filament::card>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-filament::card>

            {{-- KYC DETAILS --}}
            <x-filament::card>
                <div class="fi-section-content-ctn">
                    <div class="fi-section-content">
                        <h3 class="text-base font-semibold mb-4">KYC Details</h3>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                            <x-filament::card>
                                <div style="text-align: center; padding: 1rem;">
                                    <p style="font-size: 0.75rem; font-weight: 500; color: rgb(107 114 128); margin-bottom: 0.25rem;">
                                        Aadhaar Number
                                    </p>
                                    <p style="font-size: 1rem; font-weight: 600;">
                                        {{ $user->kyc?->aadhaar_number ?: '—' }}
                                    </p>
                                </div>
                            </x-filament::card>

                            <x-filament::card>
                                <div style="text-align: center; padding: 1rem;">
                                    <p style="font-size: 0.75rem; font-weight: 500; color: rgb(107 114 128); margin-bottom: 0.25rem;">
                                        PAN Number
                                    </p>
                                    <p style="font-size: 1rem; font-weight: 600;">
                                        {{ $user->kyc?->pan_number ?: '—' }}
                                    </p>
                                </div>
                            </x-filament::card>
                        </div>
                    </div>
                </div>
            </x-filament::card>

            {{-- BANK DETAILS --}}
            <x-filament::card>
                <div class="fi-section-content-ctn">
                    <div class="fi-section-content">
                        <h3 class="text-base font-semibold mb-4">Bank Details</h3>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                            <x-filament::card>
                                <div style="text-align: center; padding: 1rem;">
                                    <p class="text-xs text-gray-400 mb-1">Bank Name</p>
                                    <p class="font-semibold">
                                        {{ $user->bank?->bank_name ?: '—' }}
                                    </p>
                                </div>
                            </x-filament::card>

                            <x-filament::card>
                                <div style="text-align: center; padding: 1rem;">
                                    <p class="text-xs text-gray-400 mb-1">Account Number</p>
                                    <p class="font-semibold">
                                        {{ $user->bank?->account_number
                                            ? 'XXXXXX' . substr($user->bank->account_number, -4)
                                            : '—' }}
                                    </p>
                                </div>
                            </x-filament::card>

                            <x-filament::card>
                                <div style="text-align: center; padding: 1rem;">
                                    <p class="text-xs text-gray-400 mb-1">IFSC Code</p>
                                    <p class="font-semibold">
                                        {{ $user->bank?->ifsc_code ?: '—' }}
                                    </p>
                                </div>
                            </x-filament::card>

                            <x-filament::card>
                                <div style="text-align: center; padding: 1rem;">
                                    <p class="text-xs text-gray-400 mb-1">Branch Name</p>
                                    <p class="font-semibold">
                                        {{ $user->bank?->branch_name ?: '—' }}
                                    </p>
                                </div>
                            </x-filament::card>
                        </div>
                    </div>
                </div>
            </x-filament::card>

            {{-- EMERGENCY CONTACT --}}
            <x-filament::card>
                <div class="fi-section-content-ctn">
                    <div class="fi-section-content">
                        <h3 class="text-base font-semibold mb-4">Emergency Contact</h3>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                            <x-filament::card>
                                <div style="text-align: center; padding: 1rem;">
                                    <p class="text-xs text-gray-400 mb-1">Contact Name</p>
                                    <p class="font-semibold">
                                        {{ $user->emergency_contact_name ?: '—' }}
                                    </p>
                                </div>
                            </x-filament::card>

                            <x-filament::card>
                                <div style="text-align: center; padding: 1rem;">
                                    <p class="text-xs text-gray-400 mb-1">Contact Phone</p>
                                    <p class="font-semibold">
                                        {{ $user->emergency_contact_phone ?: '—' }}
                                    </p>
                                </div>
                            </x-filament::card>
                        </div>
                    </div>
                </div>
            </x-filament::card>

        </div>
    </x-filament::section>
</x-filament-panels::page>
