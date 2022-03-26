module.exports = {
  purge: ['./public/index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  darkMode: false, // or 'media' or 'class'
  content: ['./src/**/*.{html,js}'],
  theme: {
    extend: {
      fontSize: {
        '2xs': ['0.7rem', '1rem'],
      },
      spacing: {
        0.75: '0.2rem',
      },
      width: {
        160: '40rem',
      },
      fontFamily: {
        'chat-heading': ['Raleway', 'sans-serif'],
        'chat-body': ['Open Sans', 'sans-serif'],
      },
      listStyleType: {
        asterisk: '\'*\'',
      },
      gridTemplateColumns: {
        '2-auto': '1fr auto',
      },
      backgroundImage: {
        'submit-msg': 'url("/img/icons/send-message.png")',
        'loading-spinner': 'url("/img/icons/loading.png")',
        'loading-motion-reduce': 'url("/img/icons/loading-motion-reduced.png")',
        'check-mark': 'url("/img/icons/tick.png")',
      },
      backgroundSize: {
        '50%': '50%',
      },
      backgroundPosition: {
        'center-l-offset': 'top 50% left 60%',
      },
    },
  },
  variants: {
    extend: {
      backgroundColor: ['disabled'],
      backgroundImage: ['motion-reduce'],
      cursor: ['hover', 'disabled'],
      animation: ['motion-safe'],
      borderWidth: ['focus'],
      ringWidth: ['focus-visible'],
      ringOffsetWidth: ['focus-visible'],
      ringColor: ['focus-visible'],
    },
  },
  plugins: [],
};
