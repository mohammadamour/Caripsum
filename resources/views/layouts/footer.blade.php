<footer class="motora-footer" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); padding: 2rem 0; margin-top: auto; border-top: 1px solid rgba(255, 255, 255, 0.15); width: 100%;">
    <div class="container footer-content-wrapper" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.75rem;">
        <!-- Left Side: Vertical Stack of Credits & Links (Aligned with Left Grid Edge) -->
        <div class="footer-left-content" style="display: flex; flex-direction: column; gap: 0.5rem; align-items: flex-start;">
            <!-- Line 1: Author Portfolio Credit -->
            <a
                href="https://mohammadamour.github.io/Mohammad-Altayeb/"
                target="_blank"
                rel="noreferrer"
                class="footer-link-item"
                style="color: rgba(255, 255, 255, 0.9); font-size: 0.875rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s ease;"
                onmouseover="this.style.color='#ffffff'; this.style.transform='translateX(3px)';"
                onmouseout="this.style.color='rgba(255, 255, 255, 0.9)'; this.style.transform='translateX(0)';"
            >
                <i class="fas fa-globe" style="font-size: 0.95rem; opacity: 0.9; width: 16px; text-align: center;"></i>
                <span>Coded by Mohammad Al-Amour</span>
            </a>

            <!-- Line 2: GitHub Profile -->
            <a
                href="https://github.com/mohammadamour"
                target="_blank"
                rel="noreferrer"
                class="footer-link-item"
                style="color: rgba(255, 255, 255, 0.9); font-size: 0.875rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s ease;"
                onmouseover="this.style.color='#ffffff'; this.style.transform='translateX(3px)';"
                onmouseout="this.style.color='rgba(255, 255, 255, 0.9)'; this.style.transform='translateX(0)';"
            >
                <i class="fab fa-github" style="font-size: 1rem; opacity: 0.9; width: 16px; text-align: center;"></i>
                <span>GitHub Profile</span>
            </a>

            <!-- Line 3: GitHub Repository -->
            <a
                href="https://github.com/mohammadamour/Caripsum"
                target="_blank"
                rel="noreferrer"
                class="footer-link-item"
                style="color: rgba(255, 255, 255, 0.9); font-size: 0.875rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s ease;"
                onmouseover="this.style.color='#ffffff'; this.style.transform='translateX(3px)';"
                onmouseout="this.style.color='rgba(255, 255, 255, 0.9)'; this.style.transform='translateX(0)';"
            >
                <i class="fab fa-github-alt" style="font-size: 1rem; opacity: 0.9; width: 16px; text-align: center;"></i>
                <span>GitHub Repository</span>
            </a>
        </div>

        <!-- Right Side: Motora Logo and Copyright (Aligned with Right Grid Edge) -->
        <div class="footer-right-content" style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.35rem;">
            <a href="/" style="text-decoration: none; display: inline-block;">
                <x-logo variant="white" />
            </a>
            <span style="color: rgba(255, 255, 255, 0.8); font-size: 0.78rem; letter-spacing: 0.04em;">
                &copy; {{ date('Y') }} Motora. All rights reserved.
            </span>
        </div>
    </div>
</footer>
