Feature: Workflow
  In order to test that users have the correct permissions.
  We will create some users with required roles and test their access.

  @api @workflow
  Scenario: Workflow
    Given users:
      | name      | mail                | roles         | status |
      | Arthur    | arthur@sfgov.org    | writer        | 1      |
      | Penelope  | penelope@sfgov.org  | publisher     | 1      |
      | Admin     | admin@sfgov.org     | administrator | 1      |

    ## Make sure all roles are set properly.
    When I am logged in as "Admin"
    And  I visit "admin/people"
    Then I should see the text "Administrator" in the "Admin" row
    And  I should not see the text "Administrator" in the "Arthur" row
    And  I should not see the text "Publisher" in the "Arthur" row
    And  I should see the text "Writer" in the "Arthur" row
    And  I should not see the text "Administrator" in the "Penelope" row
    And  I should not see the text "Writer" in the "Penelope" row
    And  I should see the text "Publisher" in the "Penelope" row

    ## Test the admin can publish to any moderaiton state.
    When I visit "node/add/department"
    Then I should see "Draft" in the "#edit-moderation-state-wrapper" element
    And  I should see "Ready for review" in the "#edit-moderation-state-wrapper" element
    And  I should see "Published" in the "#edit-moderation-state-wrapper" element

    ## Make sure Writers cannot create Departments
    When I am logged in as "Arthur"
    And  I am on "node/add/department"
    Then I should see "Access denied"

    ## Make sure the Writers can use draft and Ready for review but not Published moderation states.
    When I visit "node/add/transaction"
    Then I should see "Draft" in the "#edit-moderation-state-wrapper" element
    And  I should see "Ready for review" in the "#edit-moderation-state-wrapper" element
    And  I should not see "Published" in the "#edit-moderation-state-wrapper" element

    ## Make sure the Writers can use the correct topic transitions.
    When I visit "node/add/topic"
    Then I should see "Draft" in the "#edit-moderation-state-wrapper" element
    And  I should see "Ready for review" in the "#edit-moderation-state-wrapper" element
    And  I should not see "Published" in the "#edit-moderation-state-wrapper" element

    ## Make sure Publishers cannot create Departments
    When I am logged in as "Penelope"
    And  I am on "node/add/department"
    Then I should see "Access denied"

    ## Make sure the Publishers can use any moderation state.
    When I visit "node/add/transaction"
    Then I should see "Draft" in the "#edit-moderation-state-wrapper" element
    And  I should see "Ready for review" in the "#edit-moderation-state-wrapper" element
    And  I should see "Published" in the "#edit-moderation-state-wrapper" element

    ## Make sure the Publishers can use the correct topic transitions.
    When I visit "node/add/topic"
    Then I should see "Draft" in the "#edit-moderation-state-wrapper" element
    And  I should see "Ready for review" in the "#edit-moderation-state-wrapper" element
    And  I should see "Published" in the "#edit-moderation-state-wrapper" element

  @api @workflow
  Scenario: Department Member Workflow
    Given users:
      | name      | mail                | roles         | status |
      | Arthur    | arthur@sfgov.org    |               | 1      |
      | Penelope  | penelope@sfgov.org  |               | 1      |
      | Admin     | admin@sfgov.org     | administrator | 1      |

    ## Verify global permissions ##
    ## Make sure all roles are set properly.
    When I am logged in as "Admin"
    And  I visit "admin/people"
    Then I should see the text "Administrator" in the "Admin" row
    And  I should not see the text "Administrator" in the "Arthur" row
    And  I should not see the text "Publisher" in the "Arthur" row
    And  I should not see the text "Writer" in the "Arthur" row
    And  I should not see the text "Administrator" in the "Penelope" row
    And  I should not see the text "Writer" in the "Penelope" row
    And  I should not see the text "Publisher" in the "Penelope" row

    ## Make sure Arthur cannot create Departments
    When I am logged in as "Arthur"
    And  I am on "node/add/department"
    Then I should see "Access denied"

    ## Make sure Arthur cannot create Transactions
    When I am on "node/add/transaction"
    Then I should see "Access denied"

    ## Make sure Arthur cannot create Topics
    When I am on "node/add/topic"
    Then I should see "Access denied"

    ## Make sure Penelope cannot create Departments
    When I am logged in as "Penelope"
    And  I am on "node/add/department"
    Then I should see "Access denied"

    ## Make sure Penelope cannot create Transactions
    When I am on "node/add/transaction"
    Then I should see "Access denied"

    ## Make sure Penelope cannot create Topics
    When I am on "node/add/topic"
    Then I should see "Access denied"

    ## Assign users to groups ##
    ## Add Author to a group 1.
    When I am logged in as "Admin"
    And  I visit "group/1/content/add/group_membership"
    And  I fill in "Username" with "Arthur"
    And  I check the box "Writer"
    And  I press the "Save" button
    Then I should see "Roles Writer"

    ## Add Penelope to a group 1.
    When I visit "group/1/content/add/group_membership"
    And  I fill in "Username" with "Penelope"
    And  I check the box "Publisher"
    And  I press the "Save" button
    Then I should see "Roles Publisher"

    ## Verify roles while still logged in as admin.
    When I visit "group/1/members"
    Then I should see the text "Writer" in the "Arthur" row
    And  I should not see the text "Publisher" in the "Arthur" row
    And  I should not see the text "Admin" in the "Arthur" row
    And  I should see the text "Publisher" in the "Penelope" row
    And  I should not see the text "Writer" in the "Penelope" row
    And  I should not see the text "Admin" in the "Penelope" row

    ## Writers ##
    ## Make sure group Writers can see group entities.
    When I am logged in as "Arthur"
    And  I am on "group/1/content"
    Then I should not see "Access denied"
    And  I should see the link "Create new entity in group"

    ## Make sure group Writers can see group content.
    When I am on "group/1/nodes"
    Then I should not see "Access denied"
    And  I should see the link "Create node"

    # Make sure the content add page is behaving nicely.
    When I visit "group/1/content/create"
    Then I should not see "Access denied"
    And  I should see the link "Group node (Topic)"
    And  I should see the link "Group node (Transaction)"
    And  I should not see the link "Group node (Department)"

    ## Make sure group Writers cannot create Departments.
    When I am on "group/1/content/create/group_node:department"
    Then I should see "Access denied"

    ## Make sure the Writers can use draft and Ready for review but not Published moderation states.
    When I visit "group/1/content/create/group_node:transaction"
    Then I should see "Draft" in the "#edit-moderation-state-wrapper" element
    And  I should see "Ready for review" in the "#edit-moderation-state-wrapper" element
    And  I should not see "Published" in the "#edit-moderation-state-wrapper" element

    ## Make sure the Writers can use the correct topic transitions.
    When I visit "group/1/content/create/group_node:topic"
    Then I should see "Draft" in the "#edit-moderation-state-wrapper" element
    And  I should see "Ready for review" in the "#edit-moderation-state-wrapper" element
    And  I should not see "Published" in the "#edit-moderation-state-wrapper" element

    ## Publishers ##
    ## Make sure group Publishers can see group entities.
    When I am logged in as "Penelope"
    And  I am on "group/1/content"
    Then I should not see "Access denied"
    And  I should see the link "Create new entity in group"

    ## Make sure group Publishers can see group content.
    When I am on "group/1/nodes"
    Then I should not see "Access denied"
    And  I should see the link "Create node"

    # Make sure the content add page is behaving nicely.
    When I visit "group/1/content/create"
    Then I should not see "Access denied"
    And  I should see the link "Group node (Topic)"
    And  I should see the link "Group node (Transaction)"
    ## @todo: The link to create department content should not exist on this page.
#    And  I should not see the link "Group node (Department)"

    ## Make sure Publishers cannot create Departments
    When I am on "group/1/content/create/group_node:department"
    Then I should see "Access denied"

    ## Make sure the Publishers can use any moderation state.
    When I visit "group/1/content/create/group_node:transaction"
    Then I should see "Draft" in the "#edit-moderation-state-wrapper" element
    And  I should see "Ready for review" in the "#edit-moderation-state-wrapper" element
    And  I should see "Published" in the "#edit-moderation-state-wrapper" element

    ## Make sure the Publishers can use the correct topic transitions.
    When I visit "group/1/content/create/group_node:topic"
    Then I should see "Draft" in the "#edit-moderation-state-wrapper" element
    And  I should see "Ready for review" in the "#edit-moderation-state-wrapper" element
    And  I should see "Published" in the "#edit-moderation-state-wrapper" element
