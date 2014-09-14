Feature: An hello world feature example

    Scenario: Just say hello to me!
        When I am on "/hello/walter"
        Then I should see "Hello Walter"

    Scenario Outline: Just say hello to everyone!
        When I am on <page>
        Then I should see <hello>

        Examples:
            | page              | hello            |
            | "/hello/walter"   | "Hello Walter"   |
            | "/hello/marco"    | "Hello Marco"    |
            | "/hello/giovanni" | "Hello Giovanni" |
            | "/hello/martina"  | "Hello Martina"  |
