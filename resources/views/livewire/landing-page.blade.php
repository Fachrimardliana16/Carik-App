<div style="min-height: 100vh; background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%); margin: 0; padding: 0;">
    <style>
        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; }
        @media (max-width: 640px) {
            .desktop-subtitle { display: none !important; }
            .mobile-subtitle { display: block !important; }
        }
        @media (min-width: 641px) {
            .desktop-subtitle { display: block !important; }
            .mobile-subtitle { display: none !important; }
        }
    </style>
    {{-- Header/Navbar --}}
    <header style="background: #0377fc; padding: 0;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 16px 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    @if($logoLight)
                        <div style="width: 50px; height: 50px; background: white; border-radius: 8px; padding: 6px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('storage/' . $logoLight) }}" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        </div>
                    @else
                        <div style="width: 50px; height: 50px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 28px; height: 28px; color: #0377fc;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h1 style="font-size: 20px; font-weight: bold; color: white; margin: 0;">{{ $companyName }}</h1>
                        <p class="desktop-subtitle" style="font-size: 13px; color: rgba(255,255,255,0.8); margin: 0;">Sistem Informasi Persuratan Digital</p>
                        <p class="mobile-subtitle" style="font-size: 12px; color: rgba(255,255,255,0.8); margin: 0; display: none;">SIPD</p>
                    </div>
                </div>
                <a href="/app/login" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: white; color: #0377fc; font-size: 14px; font-weight: 600; border-radius: 8px; text-decoration: none; transition: all 0.2s;">
                    <span>Masuk</span>
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    {{-- Hero Section --}}
    <main style="padding: 60px 24px;">
        <div style="max-width: 1200px; margin: 0 auto;">
            {{-- Hero Text --}}
            <div style="text-align: center; margin-bottom: 48px;">
                <div style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: rgba(3, 119, 252, 0.2); border: 1px solid rgba(3, 119, 252, 0.4); border-radius: 50px; color: #60a5fa; font-size: 14px; font-weight: 500; margin-bottom: 24px;">
                    <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Sistem Terverifikasi & Terintegrasi</span>
                </div>
                <h2 style="font-size: 42px; font-weight: 800; color: white; margin: 0 0 16px 0; line-height: 1.2;">
                    Layanan Persuratan <span style="color: #0377fc;">Digital</span>
                </h2>
                <p style="font-size: 18px; color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto; line-height: 1.6;">
                    Lacak status surat dan verifikasi keaslian dokumen resmi secara real-time, aman, dan transparan.
                </p>
            </div>

            {{-- Main Card --}}
            <div style="max-width: 600px; margin: 0 auto;">
                <div style="background: white; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); overflow: hidden;">
                    {{-- Tabs --}}
                    <div style="display: flex; border-bottom: 1px solid #e5e7eb;">
                        <button wire:click="setActiveTab('tracking')" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 16px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'tracking' ? 'background: #0377fc; color: white;' : 'background: #f9fafb; color: #6b7280;' }}">
                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Lacak Surat</span>
                        </button>
                        <button wire:click="setActiveTab('verification')" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 16px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; {{ $activeTab === 'verification' ? 'background: #0377fc; color: white;' : 'background: #f9fafb; color: #6b7280;' }}">
                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span>Verifikasi Dokumen</span>
                        </button>
                    </div>

                    {{-- Form Content --}}
                    <div style="padding: 32px;">
                        @if($activeTab === 'tracking')
                            <form wire:submit.prevent="performSearch">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 12px;">Nomor Surat / Nomor Agenda</label>
                                <input wire:model.defer="search" type="text" 
                                    style="width: 100%; padding: 14px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 15px; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                                    placeholder="Masukkan nomor surat atau agenda..."
                                    onfocus="this.style.borderColor='#0377fc'" 
                                    onblur="this.style.borderColor='#e5e7eb'">
                                <button type="submit" style="width: 100%; margin-top: 16px; padding: 14px; background: #0377fc; color: white; font-size: 15px; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#0261cc'" onmouseout="this.style.background='#0377fc'">
                                    Cari Surat
                                </button>
                                @if (session()->has('error'))
                                    <div style="margin-top: 16px; padding: 12px 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; color: #991b1b; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                                        <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ session('error') }}</span>
                                    </div>
                                @endif
                            </form>
                        @else
                            <form wire:submit.prevent="verifyDocument">
                                <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 12px;">Kode Validasi (QR Code)</label>
                                <div style="display: flex; gap: 12px;">
                                    <input wire:model.defer="verificationCode" id="verificationCode" type="text"
                                        style="flex: 1; padding: 14px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 15px; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                                        placeholder="Masukkan UUID dari QR Code..."
                                        onfocus="this.style.borderColor='#0377fc'" 
                                        onblur="this.style.borderColor='#e5e7eb'">
                                    <button type="button" onclick="startQrScanner()" 
                                        style="padding: 14px 16px; background: #f3f4f6; border: 2px solid #e5e7eb; border-radius: 10px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center;" title="Scan QR"
                                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                                        <svg style="width: 22px; height: 22px; color: #374151;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </button>
                                </div>
                                <button type="submit" style="width: 100%; margin-top: 16px; padding: 14px; background: #0377fc; color: white; font-size: 15px; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#0261cc'" onmouseout="this.style.background='#0377fc'">
                                    Verifikasi Dokumen
                                </button>
                                @if (session()->has('verification_error'))
                                    <div style="margin-top: 16px; padding: 12px 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; color: #991b1b; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                                        <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ session('verification_error') }}</span>
                                    </div>
                                @endif
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Quick Link --}}
                <div style="text-align: center; margin-top: 32px;">
                    <a href="{{ route('public.surat-masuk') }}" style="display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.7); font-size: 14px; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.7)'">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Input Surat Masuk untuk Tamu / Eksternal</span>
                    </a>
                </div>
            </div>

            {{-- Results Section --}}
            <div id="results" style="max-width: 700px; margin: 48px auto 0;">
                @if($resultMasuk)
                    <div style="background: white; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); overflow: hidden;">
                        <div style="background: #0377fc; padding: 16px 24px;">
                            <h3 style="font-size: 18px; font-weight: 700; color: white; margin: 0; display: flex; align-items: center; gap: 10px;">
                                <svg style="width: 22px; height: 22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                Informasi Surat Masuk
                            </h3>
                        </div>
                        <div style="padding: 24px;">
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                                <div style="background: #f8fafc; border-radius: 10px; padding: 16px;">
                                    <p style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0; font-weight: 600;">Nomor Surat</p>
                                    <p style="font-size: 15px; color: #1e293b; font-weight: 700; margin: 0;">{{ $resultMasuk->nomor_surat }}</p>
                                </div>
                                <div style="background: #f8fafc; border-radius: 10px; padding: 16px;">
                                    <p style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0; font-weight: 600;">Nomor Agenda</p>
                                    <p style="font-size: 15px; color: #1e293b; font-weight: 700; margin: 0;">{{ $resultMasuk->nomor_agenda }}</p>
                                </div>
                                <div style="background: #f8fafc; border-radius: 10px; padding: 16px; grid-column: span 2;">
                                    <p style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0; font-weight: 600;">Perihal</p>
                                    <p style="font-size: 15px; color: #1e293b; font-weight: 600; margin: 0;">{{ $resultMasuk->perihal }}</p>
                                </div>
                                <div style="background: #f8fafc; border-radius: 10px; padding: 16px; grid-column: span 2;">
                                    <p style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px 0; font-weight: 600;">Status</p>
                                    <span style="display: inline-block; padding: 6px 14px; border-radius: 50px; font-size: 13px; font-weight: 700; {{ ($resultMasuk->statusSurat?->nama === 'Signed' || $resultMasuk->status === 'Signed') ? 'background: #dcfce7; color: #166534;' : 'background: #fee2e2; color: #991b1b;' }}">
                                        {{ $resultMasuk->statusSurat?->nama ?? $resultMasuk->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($resultKeluar)
                    <div style="background: white; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); overflow: hidden;">
                        <div style="background: #0377fc; padding: 16px 24px;">
                            <h3 style="font-size: 18px; font-weight: 700; color: white; margin: 0; display: flex; align-items: center; gap: 10px;">
                                <svg style="width: 22px; height: 22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Informasi Surat Keluar
                            </h3>
                        </div>
                        <div style="padding: 24px;">
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                                <div style="background: #f8fafc; border-radius: 10px; padding: 16px;">
                                    <p style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0; font-weight: 600;">Nomor Surat</p>
                                    <p style="font-size: 15px; color: #1e293b; font-weight: 700; margin: 0;">{{ $resultKeluar->nomor_surat }}</p>
                                </div>
                                <div style="background: #f8fafc; border-radius: 10px; padding: 16px;">
                                    <p style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0; font-weight: 600;">Tanggal</p>
                                    <p style="font-size: 15px; color: #1e293b; font-weight: 700; margin: 0;">{{ $resultKeluar->tanggal_surat->format('d M Y') }}</p>
                                </div>
                                <div style="background: #f8fafc; border-radius: 10px; padding: 16px; grid-column: span 2;">
                                    <p style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0; font-weight: 600;">Perihal</p>
                                    <p style="font-size: 15px; color: #1e293b; font-weight: 600; margin: 0;">{{ $resultKeluar->perihal }}</p>
                                </div>
                                <div style="background: #f8fafc; border-radius: 10px; padding: 16px; grid-column: span 2;">
                                    <p style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px 0; font-weight: 600;">Status</p>
                                    <span style="display: inline-block; padding: 6px 14px; border-radius: 50px; font-size: 13px; font-weight: 700; background: #dbeafe; color: #1e40af;">
                                        {{ $resultKeluar->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($verificationResult)
                    <div style="background: white; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); overflow: hidden;">
                        <div style="background: #16a34a; padding: 16px 24px;">
                            <h3 style="font-size: 18px; font-weight: 700; color: white; margin: 0; display: flex; align-items: center; gap: 10px;">
                                <svg style="width: 22px; height: 22px;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Dokumen Valid & Terdaftar
                            </h3>
                        </div>
                        <div style="padding: 24px;">
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                                <div style="background: #f8fafc; border-radius: 10px; padding: 16px;">
                                    <p style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0; font-weight: 600;">Nomor Surat</p>
                                    <p style="font-size: 15px; color: #1e293b; font-weight: 700; margin: 0;">{{ $verificationResult->nomor_surat }}</p>
                                </div>
                                <div style="background: #f8fafc; border-radius: 10px; padding: 16px;">
                                    <p style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0; font-weight: 600;">Tanggal</p>
                                    <p style="font-size: 15px; color: #1e293b; font-weight: 700; margin: 0;">{{ $verificationResult->tanggal_surat->format('d M Y') }}</p>
                                </div>
                                <div style="background: #f8fafc; border-radius: 10px; padding: 16px; grid-column: span 2;">
                                    <p style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0; font-weight: 600;">Perihal</p>
                                    <p style="font-size: 15px; color: #1e293b; font-weight: 600; margin: 0;">{{ $verificationResult->perihal }}</p>
                                </div>
                                <div style="background: #f8fafc; border-radius: 10px; padding: 16px; grid-column: span 2;">
                                    <p style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0; font-weight: 600;">Penandatangan</p>
                                    <p style="font-size: 15px; color: #1e293b; font-weight: 700; margin: 0;">{{ $verificationResult->penandatangan?->name ?? '-' }}</p>
                                    @if($verificationResult->signed_at)
                                        <p style="font-size: 12px; color: #16a34a; margin: 6px 0 0 0; display: flex; align-items: center; gap: 4px; font-weight: 600;">
                                            <svg style="width: 14px; height: 14px;" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Ditandatangani Elektronik
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: auto;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 24px;">
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
                <p style="font-size: 14px; color: rgba(255,255,255,0.5); margin: 0;">&copy; {{ date('Y') }} {{ $companyName }}. Hak Cipta Dilindungi.</p>
                <div style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: rgba(255,255,255,0.5);">
                    <span>Powered by</span>
                    <span style="font-weight: 700; color: white;">CARIK<span style="color: #0377fc;">APP</span></span>
                </div>
            </div>
        </div>
    </footer>

    {{-- QR Scanner Modal --}}
    <div id="qr-scanner-modal" style="display: none; position: fixed; inset: 0; z-index: 50; align-items: center; justify-content: center; background: rgba(0,0,0,0.8); padding: 16px;">
        <div style="background: #1e293b; border-radius: 16px; width: 100%; max-width: 400px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <h3 style="font-weight: 600; color: white; margin: 0;">Scan QR Code</h3>
                <button onclick="stopQrScanner()" style="color: rgba(255,255,255,0.5); background: none; border: none; cursor: pointer; padding: 4px;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="reader" style="width: 100%; min-height: 300px; background: black; display: flex; align-items: center; justify-content: center;"></div>
            <div style="padding: 16px; border-top: 1px solid rgba(255,255,255,0.1);">
                <button onclick="stopQrScanner()" style="width: 100%; padding: 12px; background: rgba(255,255,255,0.1); border: none; border-radius: 10px; color: white; font-weight: 500; cursor: pointer;">Batal</button>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrcodeScanner = null;
        function startQrScanner() {
            const modal = document.getElementById('qr-scanner-modal');
            modal.style.display = 'flex';
            html5QrcodeScanner = new Html5Qrcode("reader");
            html5QrcodeScanner.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } }, onScanSuccess);
        }
        function stopQrScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => html5QrcodeScanner.clear()).catch(console.error);
            }
            document.getElementById('qr-scanner-modal').style.display = 'none';
        }
        function onScanSuccess(decodedText) {
            stopQrScanner();
            document.getElementById('verificationCode').value = decodedText;
            @this.set('verificationCode', decodedText);
            @this.call('verifyDocument');
        }
    </script>
</div>
