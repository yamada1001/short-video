/**
 * LeadFlow - Canvas Particles Background
 * Warm & Smart particle animation with brand colors
 */

class ParticleSystem {
    constructor(canvasId, options = {}) {
        this.canvas = document.getElementById(canvasId);
        if (!this.canvas) return;

        this.ctx = this.canvas.getContext('2d');
        this.particles = [];
        this.mouse = { x: null, y: null, radius: 150 };

        // デフォルト設定（kintone Warm Smart トンマナ）
        this.config = {
            particleCount: options.particleCount || 50,
            particleSize: options.particleSize || 2,
            particleColor: options.particleColor || ['#FF6B35', '#FFD600'],
            lineColor: options.lineColor || 'rgba(255, 107, 53, 0.15)',
            lineDistance: options.lineDistance || 120,
            speed: options.speed || 0.5,
            interactive: options.interactive !== false,
            ...options
        };

        this.init();
        this.animate();
        this.setupEventListeners();
    }

    init() {
        this.resizeCanvas();
        this.createParticles();
    }

    resizeCanvas() {
        const rect = this.canvas.parentElement.getBoundingClientRect();
        this.canvas.width = rect.width;
        this.canvas.height = rect.height;
    }

    createParticles() {
        this.particles = [];
        const isMobile = window.innerWidth < 768;
        const count = isMobile ? Math.floor(this.config.particleCount * 0.5) : this.config.particleCount;

        for (let i = 0; i < count; i++) {
            this.particles.push({
                x: Math.random() * this.canvas.width,
                y: Math.random() * this.canvas.height,
                vx: (Math.random() - 0.5) * this.config.speed,
                vy: (Math.random() - 0.5) * this.config.speed,
                size: Math.random() * this.config.particleSize + 1,
                color: this.config.particleColor[Math.floor(Math.random() * this.config.particleColor.length)]
            });
        }
    }

    drawParticle(particle) {
        this.ctx.beginPath();
        this.ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
        this.ctx.fillStyle = particle.color;
        this.ctx.globalAlpha = 0.6;
        this.ctx.fill();
        this.ctx.globalAlpha = 1;
    }

    drawLine(p1, p2, distance) {
        const opacity = 1 - (distance / this.config.lineDistance);
        this.ctx.beginPath();
        this.ctx.strokeStyle = this.config.lineColor.replace(/[\d.]+\)$/, `${opacity * 0.15})`);
        this.ctx.lineWidth = 0.5;
        this.ctx.moveTo(p1.x, p1.y);
        this.ctx.lineTo(p2.x, p2.y);
        this.ctx.stroke();
    }

    updateParticle(particle) {
        // 境界チェック
        if (particle.x < 0 || particle.x > this.canvas.width) particle.vx *= -1;
        if (particle.y < 0 || particle.y > this.canvas.height) particle.vy *= -1;

        // マウスとの相互作用
        if (this.config.interactive && this.mouse.x !== null) {
            const dx = this.mouse.x - particle.x;
            const dy = this.mouse.y - particle.y;
            const distance = Math.sqrt(dx * dx + dy * dy);

            if (distance < this.mouse.radius) {
                const force = (this.mouse.radius - distance) / this.mouse.radius;
                const angle = Math.atan2(dy, dx);
                particle.vx -= Math.cos(angle) * force * 0.2;
                particle.vy -= Math.sin(angle) * force * 0.2;
            }
        }

        // 位置更新
        particle.x += particle.vx;
        particle.y += particle.vy;

        // 速度減衰（自然な動き）
        particle.vx *= 0.99;
        particle.vy *= 0.99;

        // 最低速度の維持
        if (Math.abs(particle.vx) < 0.1) particle.vx += (Math.random() - 0.5) * 0.1;
        if (Math.abs(particle.vy) < 0.1) particle.vy += (Math.random() - 0.5) * 0.1;
    }

    animate() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        // パーティクル更新・描画
        this.particles.forEach(particle => {
            this.updateParticle(particle);
            this.drawParticle(particle);
        });

        // 線を描画（近いパーティクル同士）
        for (let i = 0; i < this.particles.length; i++) {
            for (let j = i + 1; j < this.particles.length; j++) {
                const dx = this.particles[i].x - this.particles[j].x;
                const dy = this.particles[i].y - this.particles[j].y;
                const distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < this.config.lineDistance) {
                    this.drawLine(this.particles[i], this.particles[j], distance);
                }
            }
        }

        requestAnimationFrame(() => this.animate());
    }

    setupEventListeners() {
        if (this.config.interactive) {
            this.canvas.addEventListener('mousemove', (e) => {
                const rect = this.canvas.getBoundingClientRect();
                this.mouse.x = e.clientX - rect.left;
                this.mouse.y = e.clientY - rect.top;
            });

            this.canvas.addEventListener('mouseleave', () => {
                this.mouse.x = null;
                this.mouse.y = null;
            });
        }

        window.addEventListener('resize', () => {
            this.resizeCanvas();
            this.createParticles();
        });
    }
}

// セクションごとのパーティクル初期化
document.addEventListener('DOMContentLoaded', () => {
    // Hero - 華やかに
    new ParticleSystem('particles-hero', {
        particleCount: 60,
        particleSize: 2.5,
        speed: 0.3,
        lineDistance: 150,
        particleColor: ['#FF6B35', '#FFD600', '#FF8C5A']
    });

    // Features - 控えめ
    new ParticleSystem('particles-features', {
        particleCount: 40,
        particleSize: 2,
        speed: 0.4,
        lineDistance: 100,
        particleColor: ['#FF6B35', '#FFD600']
    });

    // Functions - バランス
    new ParticleSystem('particles-functions', {
        particleCount: 50,
        particleSize: 2,
        speed: 0.35,
        lineDistance: 120,
        particleColor: ['#FFD600', '#FF6B35']
    });

    // Stats - シンプル（グラデーション背景のため）
    new ParticleSystem('particles-stats', {
        particleCount: 30,
        particleSize: 1.5,
        speed: 0.5,
        lineDistance: 80,
        particleColor: ['rgba(255, 255, 255, 0.6)', 'rgba(255, 255, 255, 0.4)'],
        lineColor: 'rgba(255, 255, 255, 0.1)'
    });

    // Testimonials - 穏やか
    new ParticleSystem('particles-testimonials', {
        particleCount: 35,
        particleSize: 2,
        speed: 0.3,
        lineDistance: 100,
        particleColor: ['#FF6B35', '#FFD600']
    });

    // Pricing - やや華やか
    new ParticleSystem('particles-pricing', {
        particleCount: 45,
        particleSize: 2,
        speed: 0.4,
        lineDistance: 110,
        particleColor: ['#FFD600', '#FF6B35', '#FF8C5A']
    });

    // CTA - 華やか（グラデーション背景）
    new ParticleSystem('particles-cta', {
        particleCount: 40,
        particleSize: 2,
        speed: 0.45,
        lineDistance: 130,
        particleColor: ['rgba(255, 255, 255, 0.7)', 'rgba(255, 255, 255, 0.5)'],
        lineColor: 'rgba(255, 255, 255, 0.12)'
    });
});
