Feature: Interested users becames active users

    Scenario: An interested user becomes an active user
        Given I am an interested user with email "walter.dalmut@gmail.com"
        When I am on homepage
        And I fill all personal fields
        Then I confirm my registration
        And I should be registered as an unconfirmed user
        And I should receive the registration email
        And I should be in the reserved area
