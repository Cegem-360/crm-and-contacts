<x-layouts.app>
    {{-- Hero Section --}}
    <section class="bg-gradient-to-b from-red-50 to-white pt-24 pb-16 lg:pt-32 lg:pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">

                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-red-100 text-red-700 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Értékesítés Modul
                </div>

                {{-- H1 --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-semibold text-gray-900 mb-6 font-heading leading-tight">
                    Professzionális <span class="text-red-600">árajánlatok</span> percek alatt
                </h1>

                {{-- Subtitle --}}
                <p class="text-lg sm:text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Mire összeáll az ajánlat, az ügyfél már máshova ment? A Cégem360 Értékesítés modul végigkíséri az üzleti folyamatot az ajánlatkéréstől a számlázásig — átlátható és nyomon követhető módon.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                    <a href="/admin" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white bg-red-600 rounded-full hover:bg-red-700 transition-colors shadow-lg">
                        Próbálja ki 14 napig ingyen
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="#kapcsolat" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-red-700 bg-white border-2 border-red-200 rounded-full hover:bg-red-50 transition-colors">
                        Demó kérése
                    </a>
                </div>

                {{-- Trust badges --}}
                <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        14 napos ingyenes próba
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Bankkártya nélkül
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Teljes funkcionalitás
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- Problem Section --}}
    <section class="py-16 lg:py-24 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Ismeri ezeket a problémákat?
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                {{-- Problem 1 --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Lassú ajánlatkészítés</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Mire összeáll az ajánlat, az ügyfél már máshova ment. Órákat tölt egy-egy ajánlat összeállításával, ahelyett hogy értékesítene.
                    </p>
                </div>

                {{-- Problem 2 --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Elveszett megrendelések</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Nem tudni, melyik ajánlatból lett rendelés, és melyik maradt nyitva. A rendelésekről azonnali áttekintés hiányzik.
                    </p>
                </div>

                {{-- Problem 3 --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Árazási káosz</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Mindenki mást mond az ügyfeleknek, nincs egységes árlista. Rossz árak, elavult kedvezmények miatti bevételkiesés.
                    </p>
                </div>

                {{-- Problem 4 --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Nincs utánkövetés</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Az elküldött ajánlatok a semmibe vesznek. Nem tudja, melyik ügyfélnél kellene épp rákérdezni.
                    </p>
                </div>
            </div>

            {{-- Solution Box --}}
            <div class="bg-gradient-to-r from-red-600 to-orange-600 dark:from-red-700 dark:to-orange-700 rounded-2xl p-8 text-center text-white">
                <h3 class="text-2xl font-semibold mb-4">A Cégem360 Értékesítés modul mindezt megoldja</h3>
                <p class="text-red-100 max-w-3xl mx-auto">
                    Professzionális árajánlatok percek alatt, automatikus utánkövetés, egységes árlistakezelés és valós idejű áttekintés minden megrendelésről.
                </p>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="funkciok" class="py-16 lg:py-24 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Minden, amire szüksége van az értékesítési folyamatok kezeléséhez
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1: Ajánlatkezelés --}}
                <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Ajánlatkezelés</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Professzionális ajánlatok gyorsan és egyszerűen.</p>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Professzionális ajánlatsablonok
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Gyors ajánlatkészítés terméklistából
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Ajánlat-verziókövetés
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            PDF export és e-mail küldés
                        </li>
                    </ul>
                </div>

                {{-- Feature 2: Rendeléskezelés --}}
                <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Rendeléskezelés</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Teljes rendelés életciklus menedzsment.</p>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Ajánlatból egy kattintással rendelés
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Rendelés státusz követés
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Részteljesítés támogatás
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Automatikus készletfoglalás
                        </li>
                    </ul>
                </div>

                {{-- Feature 3: Árazás és kedvezmények --}}
                <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Árazás és kedvezmények</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Rugalmas árképzés minden helyzetben.</p>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Többszintű árlista kezelés
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Ügyfélcsoportos kedvezmények
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mennyiségi kedvezmények
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Promóciós kampányok
                        </li>
                    </ul>
                </div>

                {{-- Feature 4: Számlázás --}}
                <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Számlázás</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Automatizált számlázás NAV integrációval.</p>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Automatikus számla generálás
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            NAV online adatszolgáltatás
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Részszámla és végszámla
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Befizetés követés
                        </li>
                    </ul>
                </div>

                {{-- Feature 5: Automatikus utánkövetés --}}
                <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Automatikus utánkövetés</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Ne veszítsen el egyetlen lehetőséget sem.</p>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Emlékeztetők nyitott ajánlatokra
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Follow-up e-mail szekvenciák
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Ajánlat-megtekintés értesítés
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Elveszett ajánlatok elemzése
                        </li>
                    </ul>
                </div>

                {{-- Feature 6: Riportok és elemzések --}}
                <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Riportok és elemzések</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Adatvezérelt döntések támogatása.</p>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Értékesítési dashboard
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Termék és ügyfél elemzés
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Értékesítői teljesítmény
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Bevétel előrejelzés
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Workflow Section --}}
    <section class="py-16 lg:py-24 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Az ajánlattól a megrendelésig — egy rendszerben
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Step 1 --}}
                <div class="relative">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                        <div class="w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                            1
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Ajánlatkészítés</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Sablonból, automatikus árazással, percek alatt
                        </p>
                    </div>
                    <div class="hidden lg:block absolute top-1/2 -right-3 transform -translate-y-1/2 text-red-300 dark:text-red-700">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="relative">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                        <div class="w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                            2
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Küldés és követés</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            PDF e-mailben, megnyitás-követéssel
                        </p>
                    </div>
                    <div class="hidden lg:block absolute top-1/2 -right-3 transform -translate-y-1/2 text-red-300 dark:text-red-700">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="relative">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                        <div class="w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                            3
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Utánkövetés</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Automatikus emlékeztető 3 nap után
                        </p>
                    </div>
                    <div class="hidden lg:block absolute top-1/2 -right-3 transform -translate-y-1/2 text-red-300 dark:text-red-700">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="relative">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                        <div class="w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                            4
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Megrendelés</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Egy kattintással a megrendelésbe
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Results Section --}}
    <section class="py-16 lg:py-24 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Eredmények számokban
                </h2>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-6 text-center">
                    <div class="text-4xl font-bold text-red-600 dark:text-red-400 mb-2">-70%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Ajánlatkészítési idő</div>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-6 text-center">
                    <div class="text-4xl font-bold text-red-600 dark:text-red-400 mb-2">+35%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Ajánlat-konverzió</div>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-6 text-center">
                    <div class="text-4xl font-bold text-red-600 dark:text-red-400 mb-2">+20%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Átlagos üzletméret</div>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-6 text-center">
                    <div class="text-4xl font-bold text-red-600 dark:text-red-400 mb-2">+80%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Utánkövetési arány</div>
                </div>
                <div class="col-span-2 lg:col-span-1 bg-red-50 dark:bg-red-900/20 rounded-2xl p-6 text-center">
                    <div class="text-4xl font-bold text-red-600 dark:text-red-400 mb-2">-50%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Adminisztrációs munka</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Integrations Section --}}
    <section id="integraciok" class="py-16 lg:py-24 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Kapcsolódjon a már használt eszközeihez
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Számlázás --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Számlázás</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Billingo, Számlázz.hu, NAV</p>
                </div>

                {{-- Webshopok --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Webshopok</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">WooCommerce, Shopify, Shoprenter</p>
                </div>

                {{-- Futárszolgálatok --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Futárszolgálatok</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">GLS, DPD, FoxPost, MPL</p>
                </div>

                {{-- Fizetés --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Fizetés</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Barion, SimplePay, OTP</p>
                </div>

                {{-- Könyvelőprogram --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Könyvelőprogram</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Kulcs-Soft, Novitax, RLB</p>
                </div>

                {{-- Nyomtatás --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Nyomtatás</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Címkenyomtatók, POS nyomtatók</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Who Uses Section --}}
    <section class="py-16 lg:py-24 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Ki használja?
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Értékesítők</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Ajánlatok és megrendelések kezelése</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Számlázás</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Számla kiállítás és követés</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Logisztika</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Kiszállítás és teljesítés</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Vezetők</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Riportok és elemzések</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials Section --}}
    <section class="py-16 lg:py-24 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Mit mondanak ügyfeleink?
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Testimonial 1 --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-1 mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-6 italic">
                        "Régen fél nap volt egy árajánlatot összerakni. Most 10 perc, és professzionálisan néz ki. Az automatikus utánkövetés pedig jelentősen növelte a konverziónkat."
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                            <span class="text-red-600 dark:text-red-400 font-semibold">VL</span>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-white">Varga László</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Értékesítési vezető, Ipari beszállító</div>
                        </div>
                    </div>
                </div>

                {{-- Testimonial 2 --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-1 mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-6 italic">
                        "A rendelésekről és számlákról azonnali áttekintésem van. Tudom, mi van teljesítve, mi nincs, és mi a kintlévőség. Végre nem Excelben kell nyomozni."
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                            <span class="text-red-600 dark:text-red-400 font-semibold">KL</span>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-white">Kiss László</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Kereskedelmi igazgató, Nagykereskedés</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Pricing Section --}}
    <section id="arak" class="py-16 lg:py-24 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Válassza ki a cégének megfelelő csomagot
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                {{-- Starter Tier --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Starter</h3>
                    <div class="mb-4">
                        <span class="text-4xl font-bold text-gray-900">4 900 Ft</span>
                        <span class="text-gray-500">/felhasználó/hó</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">Kis csapatok számára</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            500 ajánlat/hó
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Alap ajánlatsablonok
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            E-mail küldés
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Alap riportok
                        </li>
                    </ul>
                    <a href="/admin" class="block w-full text-center py-3 px-6 rounded-full text-sm font-medium border-2 border-red-200 text-red-600 hover:bg-red-50 transition-colors">
                        Kipróbálom
                    </a>
                </div>

                {{-- Professional (Featured) --}}
                <div class="bg-white rounded-2xl p-8 shadow-lg border-2 border-red-500 relative">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                            Legnépszerűbb
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Professional</h3>
                    <div class="mb-4">
                        <span class="text-4xl font-bold text-gray-900">9 900 Ft</span>
                        <span class="text-gray-500">/felhasználó/hó</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">Növekvő értékesítői csapatoknak</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Korlátlan ajánlat
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Egyedi sablonok
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Automatikus utánkövetés
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Haladó riportok
                        </li>
                    </ul>
                    <a href="/admin" class="block w-full py-3 text-center text-sm font-medium text-white bg-red-600 rounded-full hover:bg-red-700 transition-colors">
                        Kezdés most
                    </a>
                </div>

                {{-- Enterprise --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Enterprise</h3>
                    <div class="mb-4">
                        <span class="text-4xl font-bold text-gray-900">Egyedi</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">Nagyvállalatok igényeire</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Dedikált support
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Custom integrációk
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            SLA garancia
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            On-premise lehetőség
                        </li>
                    </ul>
                    <a href="#kapcsolat" class="block w-full py-3 text-center text-sm font-medium text-gray-700 border-2 border-gray-200 rounded-full hover:bg-gray-50 transition-colors">
                        Ajánlat kérése
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Related Modules Section --}}
    <section class="py-16 lg:py-24 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Kapcsolódó modulok
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                {{-- CRM modul --}}
                <a href="https://crm.cegem360.eu" class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">CRM modul</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Ügyfélkapcsolatok és pipeline kezelése az első megkeresésétől a szerződésig
                    </p>
                </a>

                {{-- Beszerzés-logisztika modul --}}
                <a href="https://beszerzes.cegem360.eu" class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Beszerzés-logisztika modul</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Készletek és szállítások kezelése — automatikus készletfoglalás az értékesítéshez
                    </p>
                </a>
            </div>
        </div>
    </section>

    {{-- FAQ Section --}}
    <section id="gyik" class="py-16 lg:py-24 bg-white dark:bg-gray-800">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Gyakran ismételt kérdések
                </h2>
            </div>

            <div class="space-y-4" x-data="{ openFaq: null }">
                {{-- FAQ 1 --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                    <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full px-6 py-4 flex justify-between items-center text-left">
                        <span class="font-medium text-gray-900 dark:text-white">Mennyire gyorsan tudok ajánlatot készíteni?</span>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform" :class="{ 'rotate-180': openFaq === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 1" x-collapse>
                        <div class="px-6 pb-4 text-gray-600 dark:text-gray-400">
                            A sablonok és a termék-katalógus segítségével egy átlagos ajánlat elkészítése 5-10 percet vesz igénybe. Az automatikus árszámítás és kedvezménykezelés további időt takarít meg.
                        </div>
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                    <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full px-6 py-4 flex justify-between items-center text-left">
                        <span class="font-medium text-gray-900 dark:text-white">Hogyan működik az automatikus utánkövetés?</span>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform" :class="{ 'rotate-180': openFaq === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 2" x-collapse>
                        <div class="px-6 pb-4 text-gray-600 dark:text-gray-400">
                            Beállíthat automatikus emlékeztetőket, amelyek X nap elteltével értesítik Önt vagy az értékesítőt a nyitott ajánlatokról. A rendszer figyeli azt is, mikor nyitotta meg az ügyfél az ajánlatot.
                        </div>
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                    <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full px-6 py-4 flex justify-between items-center text-left">
                        <span class="font-medium text-gray-900 dark:text-white">Integrálható a meglévő számlázóprogramommal?</span>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform" :class="{ 'rotate-180': openFaq === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 3" x-collapse>
                        <div class="px-6 pb-4 text-gray-600 dark:text-gray-400">
                            Igen, a rendszer integrálható a Billingo, Számlázz.hu és más népszerű magyar számlázóprogramokkal. A NAV online adatszolgáltatás automatikusan történik.
                        </div>
                    </div>
                </div>

                {{-- FAQ 4 --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                    <button @click="openFaq = openFaq === 4 ? null : 4" class="w-full px-6 py-4 flex justify-between items-center text-left">
                        <span class="font-medium text-gray-900 dark:text-white">Lehet egyedi árlistákat kezelni ügyfélcsoportonként?</span>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform" :class="{ 'rotate-180': openFaq === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 4" x-collapse>
                        <div class="px-6 pb-4 text-gray-600 dark:text-gray-400">
                            Igen, a Professional és Enterprise csomagokban lehetőség van többszintű árlisták kezelésére: viszonteladói, VIP, standard árak, valamint ügyfélcsoportos és mennyiségi kedvezmények.
                        </div>
                    </div>
                </div>

                {{-- FAQ 5 --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                    <button @click="openFaq = openFaq === 5 ? null : 5" class="w-full px-6 py-4 flex justify-between items-center text-left">
                        <span class="font-medium text-gray-900 dark:text-white">Mi történik a próbaidőszak végén?</span>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform" :class="{ 'rotate-180': openFaq === 5 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 5" x-collapse>
                        <div class="px-6 pb-4 text-gray-600 dark:text-gray-400">
                            A 14 napos próbaidőszak után kiválaszthatja a megfelelő csomagot. Az adatai megmaradnak, nincs veszteség. Ha nem kívánja folytatni, a fiókja egyszerűen deaktiválódik.
                        </div>
                    </div>
                </div>

                {{-- FAQ 6 --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                    <button @click="openFaq = openFaq === 6 ? null : 6" class="w-full px-6 py-4 flex justify-between items-center text-left">
                        <span class="font-medium text-gray-900 dark:text-white">Kapcsolódik a CRM modulhoz?</span>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform" :class="{ 'rotate-180': openFaq === 6 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 6" x-collapse>
                        <div class="px-6 pb-4 text-gray-600 dark:text-gray-400">
                            Igen, az Értékesítés és CRM modul teljesen integrált. Az ügyfelek, ajánlatok és megrendelések automatikusan szinkronizálódnak, és látja a teljes ügyfél-előzményt.
                        </div>
                    </div>
                </div>

                {{-- FAQ 7 --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                    <button @click="openFaq = openFaq === 7 ? null : 7" class="w-full px-6 py-4 flex justify-between items-center text-left">
                        <span class="font-medium text-gray-900 dark:text-white">Hány felhasználót adhatok hozzá?</span>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform" :class="{ 'rotate-180': openFaq === 7 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 7" x-collapse>
                        <div class="px-6 pb-4 text-gray-600 dark:text-gray-400">
                            Nincs felhasználói korlátozás — annyi munkatársat adhat hozzá, amennyire szüksége van. Az árazás felhasználónkénti alapon történik, így csak a tényleges használatért fizet.
                        </div>
                    </div>
                </div>

                {{-- FAQ 8 --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                    <button @click="openFaq = openFaq === 8 ? null : 8" class="w-full px-6 py-4 flex justify-between items-center text-left">
                        <span class="font-medium text-gray-900 dark:text-white">Milyen riportokat érhetek el?</span>
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform" :class="{ 'rotate-180': openFaq === 8 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 8" x-collapse>
                        <div class="px-6 pb-4 text-gray-600 dark:text-gray-400">
                            Értékesítési dashboard, termék- és ügyfél-elemzés, értékesítői teljesítmény, konverziós ráták, bevétel-előrejelzés, kampány ROI. A Professional csomagban egyedi riportokat is készíthet.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Final CTA Section --}}
    <section id="kapcsolat" class="py-16 lg:py-24 bg-gradient-to-r from-red-600 to-orange-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-semibold text-white mb-4 font-heading">
                Készen áll a professzionális értékesítésre?
            </h2>
            <p class="text-lg text-white/80 mb-8 max-w-2xl mx-auto">
                14 napos ingyenes próbaidőszak, teljes funkcionalitással. Nincs bankkártya, nincs elköteleződés.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/admin" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-red-600 bg-white rounded-full hover:bg-gray-100 transition-colors">
                    Ingyenes próba indítása
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="#" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white border-2 border-white/30 rounded-full hover:bg-white/10 transition-colors">
                    Beszéljen szakértőnkkel
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8 mb-12">
                {{-- Logo and description --}}
                <div class="col-span-2">
                    <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="CÉGEM360" class="h-10 mb-4 brightness-0 invert">
                    <p class="text-sm mb-4">
                        Komplex vállalatirányítási rendszer magyar kis- és középvállalkozásoknak.
                    </p>
                </div>

                {{-- Modulok --}}
                <div>
                    <h4 class="text-white font-semibold mb-4">Modulok</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="https://crm.cegem360.eu" class="hover:text-white transition-colors">CRM</a></li>
                        <li><a href="https://controlling.cegem360.eu" class="hover:text-white transition-colors">Kontrolling</a></li>
                        <li><a href="https://beszerzes.cegem360.eu" class="hover:text-white transition-colors">Beszerzés-logisztika</a></li>
                        <li><a href="https://ertekesites.cegem360.eu" class="hover:text-white transition-colors">Értékesítés</a></li>
                        <li><a href="https://gyartas.cegem360.eu" class="hover:text-white transition-colors">Gyártásirányítás</a></li>
                        <li><a href="https://automatizalas.cegem360.eu" class="hover:text-white transition-colors">Automatizálás</a></li>
                    </ul>
                </div>

                {{-- Megoldások --}}
                <div>
                    <h4 class="text-white font-semibold mb-4">Megoldások</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">KKV</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Nagyvállalat</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Non-profit</a></li>
                    </ul>
                </div>

                {{-- Erőforrások --}}
                <div>
                    <h4 class="text-white font-semibold mb-4">Erőforrások</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Súgó</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Akadémia</a></li>
                    </ul>
                </div>

                {{-- Cég --}}
                <div>
                    <h4 class="text-white font-semibold mb-4">Cég</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Kapcsolat</a></li>
                        <li><a href="#arak" class="hover:text-white transition-colors">Árak</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Egyedi megoldások</a></li>
                    </ul>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm">
                    &copy; {{ date('Y') }} Cégem360. Minden jog fenntartva.
                </p>
                <div class="flex gap-6 text-sm">
                    <a href="#" class="hover:text-white transition-colors">Jogi nyilatkozat</a>
                    <a href="#" class="hover:text-white transition-colors">ÁSZF</a>
                    <a href="#" class="hover:text-white transition-colors">Adatvédelem</a>
                    <a href="#" class="hover:text-white transition-colors">Cookie-k</a>
                </div>
            </div>
        </div>
    </footer>
</x-layouts.app>
