!SLIDE

#  Property Based Testing:
## Let the Computer Make Tests for You

&nbsp;
## @spinningtopsofdoom

!SLIDE

## Where does unit testing come up short?

- Excessive handcrafting
- Unit test cover small area

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

With program size edge cases and interactions make up cast bulk of bugs

!SLIDE

## Unit tests allow way too much freedom of concepts

!SLIDE

    @php
    $player1->hits($ball);
    $player2->catches($ball);
    $player1Team->outs === ($current_outs + 1);
