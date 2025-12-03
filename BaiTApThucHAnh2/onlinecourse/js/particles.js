// Optimized particle animation for background
class ParticleBackground {
    constructor(options = {}) {
        this.canvas = document.createElement('canvas');
        this.canvas.id = 'particle-canvas';
        this.canvas.style.position = 'fixed';
        this.canvas.style.top = '0';
        this.canvas.style.left = '0';
        this.canvas.style.width = '100%';
        this.canvas.style.height = '100%';
    this.canvas.style.zIndex = '-3';
        this.canvas.style.pointerEvents = 'none';
        this.canvas.style.willChange = 'transform, opacity';
        this.ctx = this.canvas.getContext('2d');

        // configuration
        this.baseCount = options.baseCount || 80; // base target
        this.particles = [];
        this._resizeTimer = null;
        this._lastTime = 0;
        this.frameSkip = 0; // used to throttle on slow devices

    document.body.insertBefore(this.canvas, document.body.firstChild);
    // Add class to reduce backdrop-filter usage (improves performance on many GPUs)
    try { document.body.classList.add('reduce-backdrop'); } catch (e) { /* ignore */ }

        this.resize();
        this.createParticles();
        this._running = true;
        this.animate();

        window.addEventListener('resize', () => this._debouncedResize());
        window.addEventListener('orientationchange', () => this._debouncedResize());
    }

    _debouncedResize() {
        if (this._resizeTimer) clearTimeout(this._resizeTimer);
        this._resizeTimer = setTimeout(() => {
            this.resize();
            this._recreateParticles();
        }, 120);
    }

    resize() {
        // DPR-aware sizing for crisp canvas without forcing huge redraws
        const dpr = Math.max(window.devicePixelRatio || 1, 1);
        const rectW = Math.max(1, Math.floor(window.innerWidth));
        const rectH = Math.max(1, Math.floor(window.innerHeight));

        // limit canvas pixel size to avoid excessive memory on very high DPR
        const maxDPR = Math.min(dpr, 2);
        this.canvas.width = Math.floor(rectW * maxDPR);
        this.canvas.height = Math.floor(rectH * maxDPR);
        this.canvas.style.width = rectW + 'px';
        this.canvas.style.height = rectH + 'px';
        this.ctx.setTransform(maxDPR, 0, 0, maxDPR, 0, 0);
        this.width = rectW;
        this.height = rectH;

        // adjust particle count for smaller screens to reduce work
        this.particleCount = Math.max(20, Math.round(this.baseCount * Math.min(1, rectW / 1366)));
    }

    _recreateParticles() {
        // rebuild particle array to match new size/count
        this.particles.length = 0;
        this.createParticles();
    }

    createParticles() {
        for (let i = 0; i < this.particleCount; i++) {
            this.particles.push(this._createParticle());
        }
    }

    _createParticle() {
        return {
            x: Math.random() * this.width,
            y: Math.random() * this.height,
            size: Math.random() * 2 + 0.6,
            speedX: (Math.random() * 0.6 - 0.3),
            speedY: (Math.random() * 0.6 - 0.3),
            opacity: Math.random() * 0.6 + 0.15
        };
    }

    drawParticles() {
        const ctx = this.ctx;
        // clear only once per frame
        ctx.clearRect(0, 0, this.width, this.height);

        // draw particles as simple circles (cheap)
        ctx.fillStyle = 'rgba(255,255,255,0.9)';
        for (let i = 0, len = this.particles.length; i < len; i++) {
            const p = this.particles[i];
            ctx.beginPath();
            ctx.globalAlpha = p.opacity;
            ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
            ctx.fill();
        }
        ctx.globalAlpha = 1;

        // draw lightweight connections for nearby particles (limit pairs)
        const maxDist = 120;
        for (let i = 0; i < this.particles.length; i++) {
            const a = this.particles[i];
            // only check a few neighbours to reduce O(n^2)
            for (let j = i + 1; j < Math.min(this.particles.length, i + 6); j++) {
                const b = this.particles[j];
                const dx = a.x - b.x;
                const dy = a.y - b.y;
                const dist = dx * dx + dy * dy;
                if (dist < maxDist * maxDist) {
                    const alpha = 0.18 * (1 - Math.sqrt(dist) / maxDist);
                    ctx.strokeStyle = `rgba(255,255,255,${alpha})`;
                    ctx.lineWidth = 0.4;
                    ctx.beginPath();
                    ctx.moveTo(a.x, a.y);
                    ctx.lineTo(b.x, b.y);
                    ctx.stroke();
                }
            }
        }
    }

    updateParticles() {
        for (let i = 0; i < this.particles.length; i++) {
            const p = this.particles[i];
            p.x += p.speedX;
            p.y += p.speedY;
            // wrap instead of bounce to reduce expensive branch changes
            if (p.x < -10) p.x = this.width + 10;
            else if (p.x > this.width + 10) p.x = -10;
            if (p.y < -10) p.y = this.height + 10;
            else if (p.y > this.height + 10) p.y = -10;
        }
    }

    animate(ts) {
        if (!this._running) return;

        // simple framerate throttle on slow devices
        if (!this._lastTime) this._lastTime = ts || performance.now();
        const now = ts || performance.now();
        const delta = now - this._lastTime;
        // target ~60fps, skip frames if rendering takes too long
        if (delta < 12) {
            requestAnimationFrame((t) => this.animate(t));
            return;
        }

        this._lastTime = now;

        this.updateParticles();
        this.drawParticles();
        requestAnimationFrame((t) => this.animate(t));
    }

    stop() {
        this._running = false;
    }
}

// Initialize particle background when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // create with a slightly lower base count to reduce repaints
    window.__particleBg = new ParticleBackground({ baseCount: 60 });
});
// Particle animation for background
class ParticleBackground {
    constructor() {
        this.canvas = document.createElement('canvas');
        this.canvas.id = 'particle-canvas';
        this.ctx = this.canvas.getContext('2d');
        this.particles = [];
        this.particleCount = 80;
        this.init();
    }

    init() {
        // Style canvas
        this.canvas.style.position = 'fixed';
        this.canvas.style.top = '0';
        this.canvas.style.left = '0';
        this.canvas.style.width = '100%';
        this.canvas.style.height = '100%';
        this.canvas.style.zIndex = '-1';
        this.canvas.style.pointerEvents = 'none';
        
        document.body.insertBefore(this.canvas, document.body.firstChild);
        
        this.resize();
        this.createParticles();
        this.animate();
        
        window.addEventListener('resize', () => this.resize());
    }

    resize() {
        this.canvas.width = window.innerWidth;
        this.canvas.height = window.innerHeight;
    }

    createParticles() {
        for (let i = 0; i < this.particleCount; i++) {
            this.particles.push({
                x: Math.random() * this.canvas.width,
                y: Math.random() * this.canvas.height,
                size: Math.random() * 3 + 1,
                speedX: Math.random() * 2 - 1,
                speedY: Math.random() * 2 - 1,
                opacity: Math.random() * 0.5 + 0.2
            });
        }
    }

    drawParticles() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        // Draw particles
        this.particles.forEach(particle => {
            this.ctx.beginPath();
            this.ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
            this.ctx.fillStyle = `rgba(255, 255, 255, ${particle.opacity})`;
            this.ctx.fill();
        });

        // Draw connections
        this.particles.forEach((particle, i) => {
            this.particles.slice(i + 1).forEach(otherParticle => {
                const dx = particle.x - otherParticle.x;
                const dy = particle.y - otherParticle.y;
                const distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < 150) {
                    this.ctx.beginPath();
                    this.ctx.strokeStyle = `rgba(255, 255, 255, ${0.2 * (1 - distance / 150)})`;
                    this.ctx.lineWidth = 0.5;
                    this.ctx.moveTo(particle.x, particle.y);
                    this.ctx.lineTo(otherParticle.x, otherParticle.y);
                    this.ctx.stroke();
                }
            });
        });
    }

    updateParticles() {
        this.particles.forEach(particle => {
            particle.x += particle.speedX;
            particle.y += particle.speedY;

            // Bounce off edges
            if (particle.x < 0 || particle.x > this.canvas.width) {
                particle.speedX *= -1;
            }
            if (particle.y < 0 || particle.y > this.canvas.height) {
                particle.speedY *= -1;
            }
        });
    }

    animate() {
        this.drawParticles();
        this.updateParticles();
        requestAnimationFrame(() => this.animate());
    }
}

// Initialize particle background when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ParticleBackground();
});