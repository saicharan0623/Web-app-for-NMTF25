<script>
particlesJS("particles-js", {
particles: {
number: {
    value: 100, // Adjust the number of particles
},
color: {
    value: "#82eefd", // Particle color
},
shape: {
    type: "circle", // Particle shape (circle, edge, triangle, polygon, etc.)
},
opacity: {
    value: 0.6, // Particle opacity
},
size: {
    value: 4, // Particle size
},
line_linked: {
    enable: true,
    distance: 150, // Distance between connected particles
    color: "#82eefd", // Line color
    opacity: 0.4,
    width: 1,
},
},
interactivity: {
detect_on: "canvas",
events: {
    onhover: {
        enable: true,
        mode: "repulse", // Interaction mode (grab, bubble, repulse, etc.)
    },
    resize: true,
},
},
});

</script>