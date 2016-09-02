!SLIDE

#  Property Based Testing:
## Let the Computer Make Tests for You

&nbsp;
## @spinningtopsofdoom

!SLIDE

## Where does unit testing come up short?

- Excessive handcrafting
- Unit tests implicitly generalize

!SLIDE

## We have an army of bugs at our door step

![Bug Army](../../images/bug_army.png)

!SLIDE

## Yet we craft a unique weapon to squash each bug

![Bug killer](../../images/bug_squasher_forge.png)

!SLIDE

## to squash each new bug we

- Come up with a new test to catch the bug
- Setup any mocks and context
- Carefully craft precise inputs
- Make sure outputs are correct

!SLIDE

Leads to tests being the same size or greater then the code they are testing

!SLIDE

## This is just for one class / module

!SLIDE

![Fractal Zoom Level 1](../.../images/fractal_zoom_level_1.png)

!SLIDE

![Fractal Zoom Level 2](../.../images/fractal_zoom_level_2.png)

!SLIDE

![Fractal Zoom Level 3](../.../images/fractal_zoom_level_3.png)

!SLIDE

The number of unit tests needed is O(c^N) where c is some constant and N is the number of interacting parts

Islands of happy paths in a sea of edge cases

!SLIDE

## Unit tests give too much free reign in code

!SLIDE

    @php
    sum(1, 2, 3) === 6;
    sum(5, 7) === 12;
    sum(1000, 2) === 1002;

!SLIDE

    @php
    $cart->addItem($shoe, 2);
    $cart->getTotal() === ($old_total + ($show->price * 2));

!SLIDE

These tests are useful and capture facts about our system. However the abstractions they capture are implicit.
