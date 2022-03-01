module.exports = {
  purge: ['./public/index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  darkMode: false, // or 'media' or 'class'
  content: ['./src/**/*.{html,js}'],
  theme: {
    extend: {
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
    },
  },
  variants: {
    extend: {
      backgroundColor: ['disabled'],
      cursor: ['hover', 'disabled'],
    },
  },
  plugins: [],
};
