Feature: My books features

    Scenario: List my books
        Given there are books
            | title                          | author          |
            | Specification by Examples      | Gojko Adzic     |
            | Bridging the communication gap | Gojko Adzic     |
            | The RSpec Book                 | David Chelimsky |
        When I am on "/book"
        Then I should see "Specification by Examples"
        And I should see "Bridging the communication gap"
        And I should see "The RSpec Book"
