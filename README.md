# chat-room

## Project setup
```
npm install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```

### Lints and fixes files
```
npm run lint
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).

## To-do / Interesting things

1. Vue local automated unit testing using [Testing Library](https://testing-library.com/docs/)
1. MySQL local automated unit testing using [The MySQL Test Framework](https://dev.mysql.com/doc/dev/mysql-server/latest/PAGE_MYSQL_TEST_RUN.html)
1. PHP local automated unit testing
1. [Build server commands (CI)](https://docs.github.com/en/actions/learn-github-actions/introduction-to-github-actions)
1. Test-driven development
1. ['Testing Vue Applications' by Edd Yerbeugh](https://www.manning.com/books/testing-vue-js-applications)
1. [Testing with a real screen reader](https://developer.mozilla.org/en-US/docs/Learn/Tools_and_testing/Cross_browser_testing/Accessibility#screenreaders)
1. Storing PHP securely in the repo. Plan: 1. Store PHP files in /public/ folder, allowing for upload in repo 2. Store sensitive database information (credentials) in config files outside of server endpoint (/www/) 3. Access sensitive information in config files through PHP 4. Maybe write some kind of test to ensure that no sensitive information is stored in PHP files?
