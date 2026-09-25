<div x-data="{
    inputMsg: '',
    optimisticText: '',
    optimisticFiles: [],
    hidePreview: false,
    scrollToBottom() {
        $nextTick(() => {
            if ($refs.chatContainer) {
                $refs.chatContainer.scrollTop = $refs.chatContainer.scrollHeight;
            }
        });
    },
    async submitChat() {
        let text = this.inputMsg.trim();
        let hasFiles = $wire.files && $wire.files.length > 0;

        if (!text && !hasFiles) return;

        // Tangkap preview gambar untuk Optimistic UI
        this.optimisticFiles = [];
        if (hasFiles && $refs.previewContainer) {
            const imgElements = $refs.previewContainer.querySelectorAll('img');
            imgElements.forEach(img => {
                this.optimisticFiles.push(img.src);
            });
        }

        this.optimisticText = text;
        this.hidePreview = true;

        this.inputMsg = '';
        this.scrollToBottom();

        try {
            await $wire.sendMessage(text);
        } finally {
            this.optimisticText = '';
            this.optimisticFiles = [];
            this.hidePreview = false;
            this.scrollToBottom();
        }
    },
    init() {
        // 1. Kunci scroll body browser khusus untuk halaman ini
        document.body.style.overflow = 'hidden';

        this.scrollToBottom();
        const observer = new MutationObserver(() => this.scrollToBottom());
        if ($refs.chatContainer) {
            observer.observe($refs.chatContainer, {
                childList: true,
                subtree: true,
                characterData: true,
                attributes: true
            });
        }
    },
    destroy() {
        // 2. Kembalikan scroll normal saat pindah ke halaman lain
        document.body.style.overflow = '';
    }
}"
    class="w-full h-[calc(100vh-6rem)] sm:h-[calc(100vh-7rem)] lg:h-[calc(100vh-8rem)] flex flex-col min-h-0 bg-white overflow-hidden rounded-2xl border border-slate-200 shadow-sm">
    @php
        $user = auth()->user();
        $expiresAt = $user->membership_expires_at;
    @endphp

    <!-- 1. Header Utama Page -->
    <div class="shrink-0 bg-white border-b border-slate-200 px-4 py-3 space-y-3">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="text-2xl">{{ $currentSubject->icon ?? '🤖' }}</span>
                <div>
                    <h1 class="font-bold text-slate-900 text-base sm:text-lg leading-tight">
                        AI Agent: {{ $currentSubject->name ?? 'Tutor' }}
                    </h1>
                    <p class="text-xs text-slate-500 hidden sm:block">Siap menjawab pertanyaan, soal, & menganalisa
                        gambar.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div
                    class="hidden md:flex items-center gap-2 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-xl text-xs">
                    <span class="text-slate-600">Paket: <strong
                            class="text-indigo-600 uppercase">{{ $user->membership_type }}</strong></span>
                    <span class="text-slate-300">|</span>
                    <span class="text-slate-500">Aktif: {{ $expiresAt ? $expiresAt->diffForHumans() : '-' }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto pb-1 pt-1 no-scrollbar">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider shrink-0 mr-1">Mapel:</span>
            @foreach ($subjects as $subject)
                <button wire:click="selectSubject({{ $subject->id }})"
                    class="shrink-0 px-3 py-1.5 rounded-xl text-xs font-medium transition-all flex items-center gap-1.5 {{ $selectedSubjectId == $subject->id ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200/60' }}">
                    <span>{{ $subject->icon }}</span>
                    <span>{{ $subject->name }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- 2. Area Riwayat Chat -->
    <div x-ref="chatContainer" class="flex-1 p-4 sm:p-6 overflow-y-auto bg-slate-50/50 overscroll-contain min-h-0">
        <div class="max-w-5xl mx-auto space-y-4">

            @if (empty($chatHistory))
                <div x-show="!optimisticText && optimisticFiles.length === 0"
                    class="py-12 flex flex-col items-center justify-center text-center text-slate-400 my-auto">
                    <span class="text-6xl mb-3">💬</span>
                    <p class="text-base font-semibold text-slate-700">Halo {{ auth()->user()->name }}!</p>
                    <p class="text-xs sm:text-sm text-slate-400 max-w-md mt-1">
                        Silakan tanyakan materi, kirim soal, atau unggah foto tugas untuk pelajaran
                        <strong class="text-indigo-600">{{ $currentSubject->name ?? '' }}</strong>.
                    </p>
                </div>
            @else
                @foreach ($chatHistory as $index => $chat)
                    <div wire:key="chat-msg-{{ $index }}-{{ count($chatHistory) }}"
                        class="flex flex-col {{ $chat['role'] === 'user' ? 'items-end' : 'items-start' }}">
                        <div
                            class="max-w-[85%] sm:max-w-[75%] rounded-2xl px-4 py-3 text-sm {{ $chat['role'] === 'user' ? 'bg-indigo-600 text-white rounded-br-none shadow-sm' : 'bg-white text-slate-800 rounded-bl-none border border-slate-200 shadow-sm' }}">

                            <!-- Render Lampiran Banyak File/Gambar jika Ada -->
                            @if (!empty($chat['files']))
                                <div class="flex flex-wrap gap-2 mb-2">
                                    @foreach ($chat['files'] as $fileItem)
                                        @if (($fileItem['type'] ?? 'image') === 'image' && !empty($fileItem['url']))
                                            <img src="{{ $fileItem['url'] }}"
                                                class="max-w-xs max-h-60 rounded-lg object-cover border border-white/20 shadow-sm"
                                                alt="Uploaded Image" />
                                        @elseif (!empty($fileItem['name']))
                                            <div
                                                class="flex items-center gap-1.5 bg-indigo-700/60 px-2.5 py-1.5 rounded-lg text-xs text-white border border-white/20">
                                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span
                                                    class="truncate max-w-[120px] font-medium">{{ $fileItem['name'] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @elseif (!empty($chat['image']))
                                <div class="mb-2">
                                    <img src="{{ $chat['image'] }}"
                                        class="max-w-xs max-h-60 rounded-lg object-cover border border-white/20 shadow-sm"
                                        alt="Uploaded Image" />
                                </div>
                            @endif

                            <!-- Efek Mengetik Teks AI (Typewriter Effect) -->
                            <div x-data="{
                                fullText: @js($chat['text'] ?? ''),
                                displayedText: '',
                                isAi: @js($chat['role'] !== 'user'),
                                isNew: @js($chat['is_new'] ?? false), // <-- Ambil flag is_new dari PHP
                                init() {
                                    // Animasi typing HANYA berjalan jika respon AI DAN merupakan pesan baru (isNew)
                                    if (!this.isAi || !this.isNew || !this.fullText) {
                                        this.displayedText = this.fullText;
                                        this.renderMath();
                                        return;
                                    }
                            
                                    let index = 0;
                                    let length = this.fullText.length;
                                    this.displayedText = '';
                            
                                    let timer = setInterval(() => {
                                        if (index < length) {
                                            index = Math.min(index + 3, length);
                                            this.displayedText = this.fullText.substring(0, index);
                                            if ($refs.chatContainer) {
                                                $refs.chatContainer.scrollTop = $refs.chatContainer.scrollHeight;
                                            }
                                        } else {
                                            clearInterval(timer);
                                            this.renderMath();
                                        }
                                    }, 15);
                                },
                                formattedContent() {
                                    if (!this.displayedText) return '';
                                    return typeof marked !== 'undefined' ? marked.parse(this.displayedText) : this.displayedText;
                                },
                                renderMath() {
                                    this.$nextTick(() => {
                                        if (window.renderMathInElement) {
                                            renderMathInElement(this.$el, {
                                                delimiters: [
                                                    { left: '$$', right: '$$', display: true },
                                                    { left: '$', right: '$', display: false },
                                                    { left: '\\(', right: '\\)', display: false },
                                                    { left: '\\[', right: '\\]', display: true }
                                                ],
                                                throwOnError: false
                                            });
                                        }
                                        if ($refs.chatContainer) {
                                            $refs.chatContainer.scrollTop = $refs.chatContainer.scrollHeight;
                                        }
                                    });
                                }
                            }" x-html="formattedContent()"
                                class="prose prose-sm max-w-none prose-p:my-1 {{ $chat['role'] === 'user' ? 'prose-invert text-white' : 'text-slate-800' }}">
                            </div>
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 px-1">
                            {{ $chat['role'] === 'user' ? 'Anda' : $currentSubject->name ?? 'AI Tutor' }}
                        </span>
                    </div>
                @endforeach
            @endif

            <!-- Bubble Sementara User (Optimistic UI) -->
            <template x-if="optimisticText || optimisticFiles.length > 0">
                <div class="flex flex-col items-end">
                    <div
                        class="max-w-[85%] sm:max-w-[75%] rounded-2xl px-4 py-3 text-sm bg-indigo-600 text-white rounded-br-none shadow-sm">

                        <template x-if="optimisticFiles.length > 0">
                            <div class="flex flex-wrap gap-2 mb-2">
                                <template x-for="(imgSrc, idx) in optimisticFiles" :key="idx">
                                    <img :src="imgSrc"
                                        class="max-w-xs max-h-60 rounded-lg object-cover border border-white/20 shadow-sm"
                                        alt="Optimistic Uploaded Image" />
                                </template>
                            </div>
                        </template>

                        <template x-if="optimisticText">
                            <p x-text="optimisticText" class="whitespace-pre-line"></p>
                        </template>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 px-1">Anda</span>
                </div>
            </template>

            <!-- Loading Indicator saat AI Berpikir/Menjawab -->
            <div wire:loading wire:target="sendMessage" class="flex items-start gap-2 pt-2">
                <div
                    class="bg-white border border-slate-200 rounded-2xl rounded-bl-none px-4 py-3 text-sm text-slate-500 shadow-sm flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span>{{ $currentSubject->name ?? 'AI' }} sedang berpikir...</span>
                </div>
            </div>

        </div>
    </div>

    <!-- 3. Form Input Chat -->
    <div class="shrink-0 p-3 sm:p-4 bg-white border-t border-slate-200">
        <form @submit.prevent="submitChat()" class="max-w-5xl mx-auto space-y-2">

            <!-- Loading Indicator Saat File Sedang Di-upload ke Server -->
            <div wire:loading wire:target="files"
                class="flex items-center gap-2 bg-indigo-50 border border-indigo-200 text-indigo-700 px-3 py-2 rounded-xl text-xs font-medium w-fit animate-pulse">
                <svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span>Mengunggah & memproses file...</span>
            </div>

            <!-- Preview Antrean File yang Selesai Di-upload -->
            @if (!empty($files))
                <div x-show="!hidePreview" x-ref="previewContainer" wire:loading.remove wire:target="files"
                    class="flex flex-wrap items-center gap-2 bg-slate-50 p-2 rounded-xl border border-slate-200 w-fit">
                    @foreach ($files as $index => $file)
                        <div class="flex items-center gap-2 bg-white p-1.5 rounded-lg border border-slate-200 relative">
                            @if (str_starts_with($file->getMimeType(), 'image/'))
                                <img src="{{ $file->temporaryUrl() }}" class="w-10 h-10 object-cover rounded-md" />
                            @else
                                <div
                                    class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-md flex items-center justify-center font-bold text-[10px]">
                                    FILE</div>
                            @endif
                            <div class="text-xs text-slate-600 pr-1">
                                <p class="font-medium truncate max-w-[120px]">{{ $file->getClientOriginalName() }}</p>
                                <p class="text-[10px] text-slate-400">{{ round($file->getSize() / 1024) }} KB</p>
                            </div>
                            <button type="button" wire:click="removeFile({{ $index }})"
                                class="bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow transition ml-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex items-center gap-2">
                <label wire:loading.class="opacity-50 pointer-events-none" wire:target="files"
                    class="p-2.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl cursor-pointer transition flex items-center justify-center shrink-0">
                    <!-- Atribut multiple & accept ditambahkan -->
                    <input type="file" wire:model="files" multiple accept="image/*,.pdf,.doc,.docx,.txt"
                        class="hidden" />
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </label>

                <input type="text" x-model="inputMsg" @keydown.enter.prevent="submitChat()"
                    placeholder="Ketik pertanyaan atau unggah foto/file untuk {{ $currentSubject->name ?? 'pelajaran' }}..."
                    class="flex-1 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl px-4 py-2.5 sm:py-3 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">

                <button type="submit" wire:loading.attr="disabled" wire:target="sendMessage, files"
                    class="px-5 py-2.5 sm:py-3 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-medium rounded-xl text-sm transition shadow-md shadow-indigo-600/20 flex items-center gap-2 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('files.*')" class="text-xs text-red-500" />
            <x-input-error :messages="$errors->get('userMessage')" class="text-xs text-red-500" />
        </form>
    </div>
</div>
