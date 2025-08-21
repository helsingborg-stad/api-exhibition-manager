# Api Alarm Manager
Creates WordPress Rest API endpoints for Exhibition events.

## Devcontainer
This project uses a devcontainer for development. This means that you can use VS Code to develop the project. To use the devcontainer, you need to install the [Remote - Containers](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers) extension for VS Code. When you have installed the extension, you can open the project in a container by clicking the green button in the bottom left corner of VS Code and select "Remote-Containers: Reopen in Container".

### Running PHPUnit tests
Run `composer test` in the terminal.

### Running PHPUnit tests with code coverage
Run `composer test:coverage` in the terminal. This will generate a code coverage report in the `tests/phpunit/.coverage` folder.
