import './bootstrap'
import $ from 'jquery'
import Alpine from 'alpinejs'
import {Howl} from 'howler'
import * as anime from 'animejs'

// 📊 DataTables e extensões
import jszip from 'jszip'
import pdfmake from 'pdfmake'
import DataTable from 'datatables.net-dt'
import 'datatables.net-buttons-dt'
import 'datatables.net-buttons/js/buttons.colVis.mjs'
import 'datatables.net-buttons/js/buttons.html5.mjs'
import 'datatables.net-buttons/js/buttons.print.mjs'
import 'datatables.net-responsive-dt'

// Config DataTables
DataTable.Buttons.jszip(jszip)
DataTable.Buttons.pdfMake(pdfmake)

window.Alpine = Alpine
window.$ = $
window.jQuery = $

/**
 * 🔊 Função segura para obter o componente Alpine de som
 */
window.getSfxComponent = function () {
    const el = document.getElementById('sfx')
    return el?.__x?.$data ?? null
}

/**
 * 🎧 Sons globais
 */
window.sfxInit = () => ({
    intro: new Howl({src: ['/sounds/bg-music.mp3'], loop: true, volume: 0.3}),
    correct: new Howl({src: ['/sounds/correct.mp3']}),
    wrong: new Howl({src: ['/sounds/wrong.mp3']}),
    click: new Howl({src: ['/sounds/click.mp3']}),
    suspense: new Howl({src: ['/sounds/suspense.mp3'], loop: true, volume: 0.5}),

    playIntro() {
        this.intro.play()
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
        anime({
            targets: el,
            scale: [1, 1.05, 1],
            duration: 900,
            easing: 'easeInOutSine'
        })
    }
})

/**
 * 🎵 Controle da música de fundo (com checagem de existência)
 */
const bgMusic = document.getElementById('bg-music')
window.toggleMusic = () => {
    if (!bgMusic) return
    if (bgMusic.paused) {
        bgMusic.volume = 0.3
        bgMusic.play()
    } else {
        bgMusic.pause()
        window.dispatchEvent(new CustomEvent('toast', {
            detail: {message: '🔇 Música de fundo pausada', type: 'warning'}
        }))
    }
}
window.setMusicVolume = (vol) => {
    if (!bgMusic) return
    bgMusic.volume = Math.min(1, Math.max(0, vol))
}

/**
 * 🛡️ Helpers para fetch com fallback seguro de JSON/HTML
 */
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
    if (ct.includes('application/json')) {
        return await response.json()
    }
    // Fallback: HTML/texto
    const text = await response.text()
    return {status: 'error', message: text, __raw: text}
}

/**
 * 🕹️ Engine principal do jogo
 */
window.gameScreen = (payload) => ({
    prizes: payload.prizes,
    secureSteps: payload.secureSteps,
    state: {
        started: false,
        step: payload.game?.current_step ?? 1,
        current_prize: payload.game?.current_prize ?? 0,
        secured_prize: payload.game?.secured_prize ?? 0,
        question: {},
        over: false,
        message: '',
        lock: false
    },
    lifelines: {
        '5050': payload.game?.lifeline_5050 ?? true,
        universitarios: payload.game?.lifeline_universitarios ?? true,
        placas: payload.game?.lifeline_placas ?? true,
        pulo: payload.game?.lifeline_pulo ?? 3,
    },
    gameId: payload.game?.id,

    async init() {
        try {
            this.state.started = true

            // 🧩 Se não houver gameId, cria novo jogo
            if (!this.gameId) {
                const response = await fetch('/play/create', {
                    method: 'POST',
                    headers: defaultHeaders(),
                    body: JSON.stringify({})
                })
                const data = await parseResponseSafe(response)
                if (!response.ok) {
                    console.error('Erro ao iniciar jogo', data)
                    alert('Erro ao iniciar jogo')
                    this.state.started = false
                    return
                }
                this.gameId = data.id
                this.state.step = data.current_step
            }

            // 📥 Carrega perguntas
            const questionsRes = await fetch(`/play/${this.gameId}/questions`, {
                method: 'GET',
                headers: defaultHeaders()
            })
            const gq = await parseResponseSafe(questionsRes)
            if (!questionsRes.ok) {
                console.error('Erro ao carregar perguntas', gq)
                alert('Erro ao carregar perguntas')
                this.state.started = false
                return
            }
            this.loadQuestion(gq)

            // 🎵 Intro
            const sfx = getSfxComponent()
            if (sfx) sfx.playIntro()
        } catch (e) {
            console.error('Falha inesperada no init()', e)
            this.state.started = false
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {message: 'Erro inesperado ao iniciar o jogo.', type: 'error'}
            }))
        }
    },

    loadQuestion(gq) {
        const item = Array.isArray(gq) ? gq.find(x => x.step === this.state.step) : null
        if (item) this.state.question = item.question
    },

    playClick() {
        const sfx = getSfxComponent()
        if (sfx) sfx.playClick()
    },

    async selectAnswer(event, answer) {
        if (this.state.lock) return
        if (!answer || typeof answer.id === 'undefined') {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {message: 'Resposta inválida.', type: 'error'}
            }))
            return
        }

        this.state.lock = true
        const btn = event.currentTarget
        btn.classList.add('animate-pulse', 'bg-yellow-600')

        const sfx = getSfxComponent()
        sfx?.playSuspense()

        try {
            const response = await fetch(`/play/${this.gameId}/answer`, {
                method: 'POST',
                headers: defaultHeaders(),
                body: JSON.stringify({step: this.state.step, answer_id: answer.id})
            })

            const result = await parseResponseSafe(response)
            sfx?.stopSuspense()

            if (!response.ok) {
                // 422/400/etc -> pinta vermelho e informa
                btn.classList.replace('bg-yellow-600', 'bg-red-600')
                sfx?.playWrong()
                console.error('Erro ao enviar resposta', result)
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        message: 'Não foi possível validar a resposta. Verifique e tente novamente.',
                        type: 'error'
                    }
                }))
                this.state.lock = false
                return
            }

            // Esperado: { status: 'correct' | 'wrong', ... }
            if (result.status === 'correct') {
                btn.classList.replace('bg-yellow-600', 'bg-green-600')
                sfx?.playCorrect()
                setTimeout(() => window.location.reload(), 1800)
            } else {
                btn.classList.replace('bg-yellow-600', 'bg-red-600')
                sfx?.playWrong()
                setTimeout(() => window.location.reload(), 2200)
            }
        } catch (e) {
            sfx?.stopSuspense()
            btn.classList.replace('bg-yellow-600', 'bg-red-600')
            sfx?.playWrong()
            console.error('Falha inesperada ao selecionar resposta', e)
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {message: 'Erro inesperado ao enviar a resposta.', type: 'error'}
            }))
            this.state.lock = false
        }
    },

    async useLifeline(type) {
        try {
            const res = await fetch(`/play/${this.gameId}/lifeline/${type}`, {
                method: 'POST',
                headers: defaultHeaders(),
                body: JSON.stringify({step: this.state.step})
            })
            const data = await parseResponseSafe(res)

            if (!res.ok) {
                console.error('Erro ao usar ajuda', data)
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {message: 'Não foi possível usar a ajuda agora.', type: 'error'}
                }))
                return
            }

            if (type === '5050') {
                this.lifelines['5050'] = false
                this.state.question.answers = this.state.question.answers.filter(a => data.keep.includes(a.id))
            } else {
                this.lifelines[type] = false
                this.state.question.answers = this.state.question.answers.map(a => {
                    const p = data.find(x => x.answer_id === a.id)?.percent ?? 0
                    return {...a, text: `${a.text} <span class='text-cyan-300'>(${p}%)</span>`}
                })
            }
        } catch (e) {
            console.error('Falha inesperada ao usar lifeline', e)
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {message: 'Erro inesperado ao usar a ajuda.', type: 'error'}
            }))
        }
    },

    async usePulo() {
        try {
            const res = await fetch(`/play/${this.gameId}/lifeline/pulo`, {
                method: 'POST',
                headers: defaultHeaders(),
                body: JSON.stringify({step: this.state.step})
            })
            const data = await parseResponseSafe(res)
            if (!res.ok) {
                console.error('Erro ao pular', data)
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {message: 'Não foi possível pular agora.', type: 'error'}
                }))
                return
            }
            this.lifelines.pulo--
            this.state.step = data.current_step
            window.location.reload()
        } catch (e) {
            console.error('Falha inesperada ao pular', e)
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {message: 'Erro inesperado ao pular pergunta.', type: 'error'}
            }))
        }
    },

    /**
     * 🚪 Quit do jogo — exibe resumo
     */
    async quitGame() {
        if (this.state.lock) return
        this.state.lock = true

        try {
            const response = await fetch(`/play/${this.gameId}/quit`, {
                method: 'POST',
                headers: defaultHeaders()
            })
            const data = await parseResponseSafe(response)

            if (!response.ok) {
                console.error('Erro ao encerrar jogo', data)
                alert('Erro ao encerrar jogo.')
                this.state.lock = false
                return
            }

            this.state.quitData = data
            this.state.over = true

            // Toca efeito sonoro
            const sfx = getSfxComponent()
            sfx?.stopSuspense()
            sfx?.playWrong()
        } catch (e) {
            console.error('Falha inesperada no quit', e)
            alert('Erro inesperado ao encerrar o jogo.')
            this.state.lock = false
        }
    }
})

Alpine.start()
