import './bootstrap'
import $ from 'jquery'
import Alpine from 'alpinejs'
import {Howl} from 'howler'
import {animate, stagger} from 'animejs'
import {loadFull} from 'tsparticles'
/* DataTables (se usar em outras telas) */
import jszip from 'jszip'
import pdfmake from 'pdfmake'
import DataTable from 'datatables.net-dt'
import 'datatables.net-buttons-dt'
import 'datatables.net-buttons/js/buttons.colVis.mjs'
import 'datatables.net-buttons/js/buttons.html5.mjs'
import 'datatables.net-buttons/js/buttons.print.mjs'
import 'datatables.net-responsive-dt'

DataTable.Buttons.jszip(jszip)
DataTable.Buttons.pdfMake(pdfmake)

window.Alpine = Alpine
window.$ = $
window.jQuery = $

/* ===== Howler unlock ===== */
let howlerUnlocked = false

function unlockHowlerOnFirstGesture() {
    if (howlerUnlocked) return
    const tryUnlock = () => {
        const s = new Howl({src: ['/sounds/click.mp3'], volume: 0, rate: 5})
        s.play()
        howlerUnlocked = true
        window.removeEventListener('click', tryUnlock, true)
        window.removeEventListener('touchstart', tryUnlock, true)
        window.removeEventListener('keydown', tryUnlock, true)
    }
    window.addEventListener('click', tryUnlock, true)
    window.addEventListener('touchstart', tryUnlock, true)
    window.addEventListener('keydown', tryUnlock, true)
}

unlockHowlerOnFirstGesture()

/* ===== SFX ===== */
window.getSfxComponent = function () {
    const el = document.getElementById('sfx')
    return el?.__x?.$data ?? null
}
window.sfxInit = () => ({
    intro: new Howl({src: ['/sounds/bg-music.mp3'], loop: true, volume: 0.25}),
    correct: new Howl({src: ['/sounds/correct.mp3'], volume: 0.9}),
    wrong: new Howl({src: ['/sounds/wrong.mp3'], volume: 0.9}),
    click: new Howl({src: ['/sounds/click.mp3'], volume: 0.7}),
    suspense: new Howl({src: ['/sounds/suspense.mp3'], loop: true, volume: 0.5}),

    playIntro() {
        this.intro.play()
    },
    stopIntro() {
        this.intro.stop()
    },
    playCorrect() {
        this.correct.play()
    },
    playWrong() {
        this.wrong.play()
    },
    playClick() {
        this.click.play()
    },
    playSuspense() {
        this.suspense.play()
    },
    stopSuspense() {
        this.suspense.stop()
    },

    pulse(el) {
        animate(el, {keyframes: [{scale: 1.05}, {scale: 1}], duration: 500, easing: 'easeInOutSine'})
    },
    shake(el) {
        animate(el, {
            keyframes: [{translateX: -6}, {translateX: 6}, {translateX: -4}, {translateX: 4}, {translateX: 0}],
            duration: 400, easing: 'easeInOutQuad'
        })
    },
    flash(el, from = '#fff59d22', to = '#fff59d66') {
        animate(el, {backgroundColor: [from, to, 'rgba(0,0,0,0)'], duration: 700, easing: 'easeInOutQuad'})
    }
})

/* ===== Música de fundo opcional ===== */
const bgMusic = document.getElementById('bg-music')
window.toggleMusic = () => {
    if (!bgMusic) return
    if (bgMusic.paused) {
        bgMusic.volume = 0.25;
        bgMusic.play()
    } else {
        bgMusic.pause()
        window.dispatchEvent(new CustomEvent('toast', {
            detail: {
                message: '🔇 Música de fundo pausada',
                type: 'warning'
            }
        }))
    }
}
window.setMusicVolume = (vol) => {
    if (bgMusic) bgMusic.volume = Math.min(1, Math.max(0, vol))
}

/* ===== HTTP ===== */
function defaultHeaders() {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? ''
    return {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrf
    }
}

async function parseResponseSafe(response) {
    const ct = response.headers.get('content-type') || ''
    if (ct.includes('application/json')) return await response.json()
    const text = await response.text()
    return {status: 'error', message: text, __raw: text}
}

/* ===== Animações utilitárias ===== */
function fadeOutIn(container, onHalf) {
    // animate(container, {
    //     opacity: [1, 0], duration: 220, easing: 'easeInOutQuad',
    //     complete: () => {
    //         onHalf?.()
    //         animate(container, {opacity: [0, 1], duration: 260, easing: 'easeInOutQuad'})
    //     }
    // })

    $(container).fadeOut(function () {
        onHalf?.();
        $(this).fadeIn();
    })
}

function glow(el, color = 'rgba(255, 193, 7, 0.65)') {
    animate(el, {
        boxShadow: [`0 0 0px 0px ${color}`, `0 0 24px 4px ${color}`, `0 0 0px 0px ${color}`],
        duration: 800, easing: 'easeInOutQuad'
    })
}

/* ===== Normalizador de perguntas =====
   Aceita:
   - [{ step, question: { text, answers } }, ...]
   - [{ step, text, answers }, ...]
   - { step, question: {...} }  ou  { text, answers }
*/
function normalizeQuestionForStep(gq, step) {
    if (!gq) return null
    // Se veio array, tenta por step; senão, pega a primeira
    let item = Array.isArray(gq) ? (gq.find(x => x?.step === step) ?? gq[0]) : gq
    if (!item) return null
    // Se encapsulado em "question", pega o payload interno
    const q = item.question ?? item
    // Uniformiza campos esperados no front
    const text = q.text ?? q.pergunta ?? q.title ?? ''
    const answers = q.answers ?? q.alternatives ?? q.opcoes ?? []
    // Alguns backends usam {id, label, text} ou {id, letra, descricao}
    const normalizedAnswers = answers.map((a, idx) => ({
        id: a.id ?? a.answer_id ?? a.value ?? idx,
        label: a.label ?? a.letra ?? String.fromCharCode(65 + idx),
        text: a.text ?? a.descricao ?? String(a)
    }))
    return {...q, text, answers: normalizedAnswers}
}

/* ===== GAME ENGINE (Alpine) ===== */
window.gameScreen = (payload) => ({
    prizes: payload.prizes,
    secureSteps: payload.secureSteps,
    gameId: payload.game?.id,

    state: {
        started: false,
        step: payload.game?.current_step ?? 1,
        current_prize: payload.game?.current_prize ?? 0,
        secured_prize: payload.game?.secured_prize ?? 0,
        question: {}, // { text, answers: [] }
        over: false,
        message: '',
        lock: false,
        loading: false,
        evaluating: false,
        quitData: null,
        lastResult: null
    },

    lifelines: {
        '5050': payload.game?.lifeline_5050 ?? true,
        universitarios: payload.game?.lifeline_universitarios ?? true,
        placas: payload.game?.lifeline_placas ?? true,
        pulo: payload.game?.lifeline_pulo ?? 3,
    },

    async init() {
        const sfx = getSfxComponent()
        try {
            this.state.started = true
            this.state.loading = true
            sfx?.playIntro()

            if (!this.gameId) {
                const response = await fetch('/play/create', {
                    method: 'POST',
                    headers: defaultHeaders(),
                    body: JSON.stringify({})
                })
                const data = await parseResponseSafe(response)
                if (!response.ok) throw new Error('Erro ao iniciar jogo')
                this.gameId = data.id
                this.state.step = data.current_step
            }

            await this.fetchAndLoadQuestions()

            const qCard = document.getElementById('question-card')
            if (qCard) {
                qCard.style.opacity = 0;
                animate(qCard, {opacity: [0, 1], translateY: [-8, 0], duration: 420, easing: 'easeOutQuad'})
            }
        } catch (e) {
            console.error(e)
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    message: 'Falha ao iniciar o jogo.',
                    type: 'error'
                }
            }))
        } finally {
            this.state.loading = false
        }
    },

    async fetchAndLoadQuestions() {
        const res = await fetch(`/play/${this.gameId}/questions`, {method: 'GET', headers: defaultHeaders()})
        const gq = await parseResponseSafe(res)
        if (!res.ok) throw new Error('Erro ao carregar perguntas')

        const container = document.getElementById('question-area')

        const apply = () => {
            const q = normalizeQuestionForStep(gq, this.state.step)
            if (!q) {
                // mostra placeholder para debug
                this.state.question = {text: 'Nenhuma pergunta encontrada para esta etapa.', answers: []}
            } else {
                this.state.question = q
            }
        }

        if (container) fadeOutIn(container, apply)
        else apply()
    },

    playClick() {
        getSfxComponent()?.playClick()
    },

    async selectAnswer(event, answer) {
        if (this.state.lock || this.state.evaluating) return
        if (!answer || typeof answer.id === 'undefined') return

        const btn = event.currentTarget
        const sfx = getSfxComponent()
        this.state.lock = true
        this.state.evaluating = true

        btn.classList.add('ring-4', 'ring-amber-400', 'animate-pulse')
        glow(btn)
        sfx?.playSuspense()

        try {
            this.state.loading = true
            const response = await fetch(`/play/${this.gameId}/answer`, {
                method: 'POST', headers: defaultHeaders(),
                body: JSON.stringify({step: this.state.step, answer_id: answer.id})
            })
            const result = await parseResponseSafe(response)
            this.state.loading = false
            sfx?.stopSuspense()

            if (!response.ok) {
                btn.classList.remove('animate-pulse')
                btn.classList.add('bg-red-700/70')
                sfx?.playWrong();
                getSfxComponent()?.shake(btn)
                this.state.lock = false;
                this.state.evaluating = false
                return
            }

            if (result.status === 'correct') {
                btn.classList.remove('animate-pulse')
                btn.classList.add('bg-green-600', 'text-white')
                sfx?.playCorrect();
                this.state.lastResult = 'correct'
                const prizeRow = document.querySelector(`[data-prize-step="${this.state.step}"]`)
                if (prizeRow) getSfxComponent()?.flash(prizeRow)
                await this.progressToNextStep()
            } else {
                btn.classList.remove('animate-pulse')
                btn.classList.add('bg-red-600', 'text-white')
                sfx?.playWrong();
                getSfxComponent()?.shake(btn)
                await this.finishAsLoseAll()
            }
        } catch (e) {
            console.error('Falha na resposta', e)
            this.state.loading = false;
            sfx?.stopSuspense();
            sfx?.playWrong()
            this.state.lock = false;
            this.state.evaluating = false
        }
    },

    async progressToNextStep() {
        const area = document.getElementById('question-area')
        fadeOutIn(area, async () => {
            this.state.step += 1
            await this.fetchAndLoadQuestions()

            const options = document.querySelectorAll('[data-answer]')
            animate(options, {
                opacity: [0, 1], translateX: [-10, 0],
                delay: stagger(60), duration: 220, easing: 'easeOutQuad'
            })
            this.state.lock = false;
            this.state.evaluating = false
        })
    },

    async finishAsLoseAll() {
        try {
            this.state.loading = true
            const response = await fetch(`/play/${this.gameId}/quit`, {method: 'POST', headers: defaultHeaders()})
            const data = await parseResponseSafe(response)
            this.state.loading = false

            if (!response.ok) {
                this.state.quitData = {message: 'Jogo encerrado!', current_prize: 0, secured_prize: 0, finished: 1}
            } else {
                data.current_prize = 0;
                data.secured_prize = 0
                this.state.quitData = data
            }
        } catch (e) {
            console.error('Erro ao encerrar (lose all)', e)
            this.state.quitData = {message: 'Jogo encerrado!', current_prize: 0, secured_prize: 0, finished: 1}
        }
        this.state.over = true;
        this.state.lock = true;
        this.state.evaluating = false
        getSfxComponent()?.stopSuspense();
        getSfxComponent()?.stopIntro()
    },

    async useLifeline(type) {
        if (this.state.lock || this.state.evaluating) return
        const sfx = getSfxComponent()
        this.state.lock = true;
        this.state.loading = true;
        sfx?.playSuspense()

        try {
            const res = await fetch(`/play/${this.gameId}/lifeline/${type}`, {
                method: 'POST', headers: defaultHeaders(), body: JSON.stringify({step: this.state.step})
            })
            const data = await parseResponseSafe(res)
            this.state.loading = false;
            sfx?.stopSuspense()

            if (!res.ok) {
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        message: 'Não foi possível usar a ajuda agora.',
                        type: 'error'
                    }
                }))
                this.state.lock = false
                return
            }

            const lifelineBtn = document.querySelector(`[data-lifeline="${type}"]`)
            if (lifelineBtn) getSfxComponent()?.flash(lifelineBtn, '#4dd0e166', '#26c6da99')

            if (type === '5050') {
                this.lifelines['5050'] = false
                const keep = data.keep || []
                const removed = []
                this.state.question.answers = this.state.question.answers.filter(a => {
                    const keepIt = keep.includes(a.id)
                    if (!keepIt) removed.push(a)
                    return keepIt
                })
                removed.forEach(a => {
                    const el = document.querySelector(`[data-answer-id="${a.id}"]`)
                    if (el) animate(el, {opacity: [1, 0], duration: 220, easing: 'easeInOutQuad'})
                })
            } else {
                this.lifelines[type] = false
                this.state.question.answers = this.state.question.answers.map(a => {
                    const p = data.find(x => x.answer_id === a.id)?.percent ?? 0
                    return {...a, hintPercent: p}
                })
                const percEls = document.querySelectorAll('[data-answer-percent]')
                animate(percEls, {
                    opacity: [0, 1],
                    translateY: [-6, 0],
                    delay: stagger(50),
                    duration: 220,
                    easing: 'easeOutQuad'
                })
            }
        } catch (e) {
            console.error('Falha ao usar lifeline', e)
            this.state.loading = false;
            sfx?.stopSuspense()
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    message: 'Erro inesperado ao usar a ajuda.',
                    type: 'error'
                }
            }))
        } finally {
            this.state.lock = false
        }
    },

    async usePulo() {
        if (this.state.lock || this.state.evaluating || !this.lifelines.pulo) return
        const sfx = getSfxComponent()
        this.state.lock = true;
        this.state.loading = true;
        sfx?.playSuspense()

        try {
            const res = await fetch(`/play/${this.gameId}/lifeline/pulo`, {
                method: 'POST', headers: defaultHeaders(), body: JSON.stringify({step: this.state.step})
            })
            const data = await parseResponseSafe(res)
            this.state.loading = false;
            sfx?.stopSuspense()

            if (!res.ok) {
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        message: 'Não foi possível pular agora.',
                        type: 'error'
                    }
                }))
                this.state.lock = false
                return
            }
            this.lifelines.pulo--

            const area = document.getElementById('question-area')
            fadeOutIn(area, async () => {
                this.state.step = data.current_step
                await this.fetchAndLoadQuestions()
                const options = document.querySelectorAll('[data-answer]')
                animate(options, {
                    opacity: [0, 1],
                    translateX: [10, 0],
                    delay: stagger(60),
                    duration: 220,
                    easing: 'easeOutQuad'
                })
                this.state.lock = false
            })
        } catch (e) {
            console.error('Falha ao pular', e)
            this.state.loading = false;
            sfx?.stopSuspense()
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    message: 'Erro inesperado ao pular pergunta.',
                    type: 'error'
                }
            }))
            this.state.lock = false
        }
    },

    async quitGame() {
        if (this.state.lock) return
        const sfx = getSfxComponent()
        this.state.lock = true;
        this.state.loading = true;
        sfx?.playSuspense()

        try {
            const response = await fetch(`/play/${this.gameId}/quit`, {method: 'POST', headers: defaultHeaders()})
            const data = await parseResponseSafe(response)
            this.state.loading = false;
            sfx?.stopSuspense()
            if (!response.ok) {
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        message: 'Erro ao encerrar o jogo.',
                        type: 'error'
                    }
                }))
                this.state.lock = false
                return
            }
            this.state.quitData = data;
            this.state.over = true
            sfx?.playWrong();
            sfx?.stopIntro()
        } catch (e) {
            console.error('Falha no quit', e)
            this.state.loading = false;
            sfx?.stopSuspense()
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    message: 'Erro inesperado ao encerrar o jogo.',
                    type: 'error'
                }
            }))
            this.state.lock = false
        }
    }
})

/* ===== Welcome (animação/partículas, se usar) ===== */
window.welcome = async () => {
    animate('.rank-item', {
        opacity: [0, 1],
        translateY: [-10, 0],
        delay: stagger(80),
        duration: 400,
        easing: 'easeOutQuad'
    })
    await loadFull(tsParticles)
    tsParticles.load('tsparticles', {
        background: {color: 'transparent'}, fullScreen: {enable: false}, fpsLimit: 60,
        particles: {
            number: {value: 50},
            color: {value: ['#FACC15', '#FCD34D', '#FDE68A', '#FFFFFF']},
            shape: {type: ['circle', 'square', 'polygon']},
            opacity: {value: 0.8},
            size: {value: {min: 2, max: 5}},
            move: {
                enable: true,
                direction: 'bottom',
                speed: {min: 1, max: 3},
                outModes: {default: 'out'},
                gravity: {enable: true, acceleration: 0.5}
            },
            rotate: {value: {min: 0, max: 360}, direction: 'random', animation: {enable: true, speed: 5}}
        },
        detectRetina: true
    })
}

/* ===== Toast ===== */
window.showToast = (message, type = 'info') => {
    const container = document.getElementById('toast-container')
    if (!container) return
    const toast = document.createElement('div')
    toast.classList.add('toast-item', 'px-4', 'py-3', 'rounded-lg', 'shadow-lg', 'mb-2', 'flex', 'items-center', 'justify-between', 'transition', 'duration-300', 'opacity-0', 'translate-y-2')
    const colors = {
        success: 'bg-green-600 text-white',
        error: 'bg-red-600 text-white',
        warning: 'bg-yellow-500 text-black',
        info: 'bg-sky-600 text-white'
    }
    toast.className += ` ${colors[type] || colors.info}`
    const icons = {success: '✅', error: '❌', warning: '⚠️', info: 'ℹ️'}
    toast.innerHTML = `<div class="flex items-center gap-2"><span>${icons[type] || icons.info}</span><span>${message}</span></div><button class="ml-4 font-bold hover:opacity-80">&times;</button>`
    toast.querySelector('button').addEventListener('click', () => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 300)
    })
    container.appendChild(toast)
    setTimeout(() => toast.classList.remove('opacity-0', 'translate-y-2'), 10)
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 300)
    }, 4000)
}

Alpine.start()
