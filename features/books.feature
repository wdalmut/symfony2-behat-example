Feature: My books features
    Background:
        Given there are books
            | title                          | author          |
            | Bridging the communication gap | Gojko Adzic     |
            | The RSpec Book                 | David Chelimsky |
            | Specification by Examples      | Gojko Adzic     |

    Scenario: List my books
        When I am on "/book/"
        Then I should see "Specification by Examples"
        And I should see "Bridging the communication gap"
        And I should see "The RSpec Book"
