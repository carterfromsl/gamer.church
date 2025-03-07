(function($) {
    'use strict';
    
    class SystemDashboard {
        constructor(element) {
            this.element = element;
            this.clientInfo = this.element.find('.client-details');
            this.lastFrameTime = performance.now();
            this.frameCount = 0;
            this.lastFpsUpdate = this.lastFrameTime;
            this.fps = 0;
            
            this.init();
        }
        
        init() {
            this.updateClientInfo();
            this.startPerformanceMonitoring();
            this.attachEventListeners();
        }
        
        updateClientInfo() {
            const browserInfo = this.detectBrowser();
            const screenInfo = this.getScreenInfo();
            const languageInfo = this.getLanguageInfo();
            
            this.clientInfo.find('.browser-info').html(`
                <strong>Browser:</strong> ${browserInfo.name} ${browserInfo.version}<br>
                <strong>User Agent:</strong> ${navigator.userAgent}
            `);
            
            this.clientInfo.find('.screen-info').html(`
                <strong>Screen Resolution:</strong> ${screenInfo.width}x${screenInfo.height}<br>
                <strong>Window Size:</strong> ${screenInfo.windowWidth}x${screenInfo.windowHeight}<br>
            `);
            
            this.clientInfo.find('.language-info').html(`
                <strong>Language:</strong> ${languageInfo.language}<br>
                <strong>Time Zone:</strong> ${languageInfo.timeZone}
            `);
        }
        
        detectBrowser() {
            const ua = navigator.userAgent;
            let name = 'Unknown';
            let version = '';
            
            if (/firefox/i.test(ua)) {
                name = 'Firefox';
                version = ua.match(/Firefox\/([\d.]+)/)?.[1] || '';
            } else if (/chrome|chromium/i.test(ua)) {
                name = 'Chrome';
                version = ua.match(/(?:Chrome|Chromium)\/([\d.]+)/)?.[1] || '';
            } else if (/safari/i.test(ua)) {
                name = 'Safari';
                version = ua.match(/Version\/([\d.]+)/)?.[1] || '';
            } else if (/edge/i.test(ua)) {
                name = 'Edge';
                version = ua.match(/Edge\/([\d.]+)/)?.[1] || '';
            }
            
            return { name, version };
        }
        
        getScreenInfo() {
            return {
                width: window.screen.width,
                height: window.screen.height,
                windowWidth: $(window).width(),
                windowHeight: $(window).height()
            };
        }
        
        getLanguageInfo() {
            return {
                language: navigator.language,
                timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone
            };
        }
        
        updatePerformanceMetrics() {
            const now = performance.now();
            const elapsed = now - this.lastFpsUpdate;
            
            if (elapsed >= 1000) {
                this.fps = Math.round((this.frameCount * 1000) / elapsed);
                this.frameCount = 0;
                this.lastFpsUpdate = now;
                
                const memory = performance.memory ? {
                    total: Math.round(performance.memory.totalJSHeapSize / 1048576),
                    used: Math.round(performance.memory.usedJSHeapSize / 1048576)
                } : null;
                
                this.clientInfo.find('.performance-info').html(`
                    ${memory ? `<strong>Memory Usage:</strong> ${memory.used}MB / ${memory.total}MB<br>` : ''}
                    <strong>Session Duration:</strong> ${Math.round(performance.now()) / 1000}s
                `);
            }
            
            this.frameCount++;
            requestAnimationFrame(() => this.updatePerformanceMetrics());
        }
        
        startPerformanceMonitoring() {
            requestAnimationFrame(() => this.updatePerformanceMetrics());
        }
        
        attachEventListeners() {
            $(window).on('resize', () => this.updateClientInfo());
            $(window).on('orientationchange', () => this.updateClientInfo());
        }
    }
    
    // Initialize all system dashboards on the page
    $('.system-dashboard').each(function() {
        new SystemDashboard($(this));
    });
    
})(jQuery);
