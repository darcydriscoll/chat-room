module.exports = {
  purge: ['./public/index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  darkMode: false, // or 'media' or 'class'
  content: ['./src/**/*.{html,js}'],
  theme: {
    extend: {
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
      cursor: ['hover', 'disabled'],
      animation: ['motion-safe'],
    },
  },
  plugins: [],
};
