// main.js
document.addEventListener('DOMContentLoaded', () => {
  // Initialize all features
  initSmoothScroll();
  initInfiniteScroll();
  initParticles();
  initMobileMenu();
  initFAQ();
  initRegisterButton();
  initAOS();
});

// Navigation and scroll functionality
const initSmoothScroll = () => {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
      });
    });
  });
};

// Infinite scroll for cards
const initInfiniteScroll = () => {
  const scrollContainer = document.querySelector('.scroll-content');
  if (scrollContainer) {
    const cards = [...scrollContainer.children];
    cards.forEach(card => {
      const clone = card.cloneNode(true);
      scrollContainer.appendChild(clone);
    });
  }
};

// Particles configuration
const initParticles = () => {
  if (typeof particlesJS !== 'undefined') {
    particlesJS("particles-js", {
      particles: {
        number: { value: 100 },
        color: { value: "#800000" },
        shape: { type: "circle" },
        opacity: { value: 0.6 },
        size: { value: 4 },
        line_linked: {
          enable: true,
          distance: 150,
          color: "#ff0000",
          opacity: 0.4,
          width: 1
        }
      },
      interactivity: {
        detect_on: "canvas",
        events: {
          onhover: {
            enable: true,
            mode: "repulse"
          },
          resize: true
        }
      }
    });
  }
};

// Mobile menu handling
const initMobileMenu = () => {
  const menuBtn = document.querySelector('.menu-btn');
  const navLinks = document.querySelector('.nav-links');
  
  if (menuBtn && navLinks) {
    const toggleMenu = () => {
      menuBtn.classList.toggle('active');
      navLinks.classList.toggle('active');
      document.body.classList.toggle('menu-open');
    };

    menuBtn.addEventListener('click', toggleMenu);

    // Close menu when clicking links
    navLinks.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        menuBtn.classList.remove('active');
        navLinks.classList.remove('active');
        document.body.classList.remove('menu-open');
      });
    });
  }
};

// FAQ section functionality
const initFAQ = () => {
  const questions = document.querySelectorAll('.faq-question');
  
  questions.forEach(button => {
    const answer = button.nextElementSibling;
    const chevron = button.querySelector('.chevron');
    
    // Initially hide answers
    if (answer) {
      answer.style.display = 'none';
    }

    button.addEventListener('click', () => {
      if (!answer || !chevron) return;
      
      const isHidden = answer.style.display === 'none';
      answer.style.display = isHidden ? 'block' : 'none';
      chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0)';
    });
  });
};

// Register button behavior
const initRegisterButton = () => {
  const registerBtn = document.getElementById('registerBtn');
  
  if (registerBtn) {
    window.addEventListener('scroll', () => {
      const showButton = document.documentElement.scrollTop > 200;
      registerBtn.classList.toggle('hidden', !showButton);
    });

    registerBtn.addEventListener('click', () => {
      const registerSection = document.getElementById('register');
      if (registerSection) {
        registerSection.scrollIntoView({ behavior: 'smooth' });
      }
    });
  }
};

// AOS initialization
const initAOS = () => {
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 2000,
      once: true,
      offset: 200
    });
  }
};