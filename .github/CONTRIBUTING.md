## Contributing

We welcome contributions to this repository. If you would like to contribute,
please follow these guidelines:

### Commit Conventions

We use the [Conventional Commits](https://www.conventionalcommits.org) for our
commit messages. This means that each commit message should have a type, scope,
and subject, like so:

`<type>(<scope>): <subject>`

- `<type>`: A description of the type of change made. Can be one of the following:
    - `feat`: A new feature
    - `fix`: A bug fix
    - `docs`: Documentation changes
    - `style`: Changes that do not affect the code's functionality (e.g. whitespace)
    - `refactor`: Code changes that do not fix a bug or add a feature
    - `perf`: Performance improvements
    - `test`: Adding missing tests or correcting existing tests
    - `build`: Changes to the build process or external dependencies
    - `ci`: Changes to our CI configuration files and scripts
    - `chore`: Other changes that don't modify src or test files
- `<scope>`: The scope of the change (e.g. business, creator)
- `<subject>`: A brief description of the change in present tense

For example, a valid commit message might look like this:

`feat(business): Add new payment method`

Please ensure that your commit messages adhere to this convention when making
changes to the codebase. This helps us keep our commit history organized and
easy to follow.

If you make a change that only affects documentation, you can add the
`[skip ci]` option to your commit message to avoid triggering a new build of
the app.

Thank you for contributing!