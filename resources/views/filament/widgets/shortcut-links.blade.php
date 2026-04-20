<x-filament-widgets::widget>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
        {{-- Ambil Antrian --}}
        <a href="/ambil-antrian" target="_blank"
           style="display: block; text-decoration: none; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.06); transition: all 0.2s;"
           onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(99,102,241,0.15)'; this.style.borderColor='#a5b4fc';"
           onmouseout="this.style.transform=''; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.06)'; this.style.borderColor='#e2e8f0';">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 56px; height: 56px; border-radius: 12px; background: linear-gradient(135deg, #6366f1, #9333ea); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <div>
                    <div style="font-size: 1rem; font-weight: 700; color: #1e293b;">Ambil Antrian</div>
                    <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Halaman pengambilan nomor antrian</div>
                </div>
            </div>
        </a>

        {{-- Display --}}
        <a href="/display" target="_blank"
           style="display: block; text-decoration: none; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.06); transition: all 0.2s;"
           onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(16,185,129,0.15)'; this.style.borderColor='#6ee7b7';"
           onmouseout="this.style.transform=''; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.06)'; this.style.borderColor='#e2e8f0';">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 56px; height: 56px; border-radius: 12px; background: linear-gradient(135deg, #10b981, #0d9488); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <div style="font-size: 1rem; font-weight: 700; color: #1e293b;">Display Antrian</div>
                    <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Layar panggilan & video</div>
                </div>
            </div>
        </a>

        {{-- Operator --}}
        <a href="/operator" target="_blank"
           style="display: block; text-decoration: none; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.06); transition: all 0.2s;"
           onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(245,158,11,0.15)'; this.style.borderColor='#fcd34d';"
           onmouseout="this.style.transform=''; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.06)'; this.style.borderColor='#e2e8f0';">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 56px; height: 56px; border-radius: 12px; background: linear-gradient(135deg, #f59e0b, #ea580c); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(245,158,11,0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div style="font-size: 1rem; font-weight: 700; color: #1e293b;">Operator</div>
                    <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Panel petugas loket</div>
                </div>
            </div>
        </a>
    </div>
</x-filament-widgets::widget>
