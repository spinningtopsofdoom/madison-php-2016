!SLIDE

# Finding Properties to test

!SLIDE

##Here's some example properties

!SLIDE

#Compatibility with old system

![Legacy vs New](../../images/legacy_vs_new.png)

!SLIDE

#Examples
- Legacy Code vs Refactored code
- Naive Algorithm vs Optimized Algorithm

!SLIDE

#The Impossible never happens

![That is impossible](../../impossible.png)

!SLIDE

#Examples
- The end state is only reachable though specific actions
- The number of people in a house should be the sum of people in the rooms
- A customers order total cost is a negative amount

!SLIDE

#Actions that are always additive or always subtractive
![Monotonic Operation](../../images/monotonic.png)

!SLIDE

#Examples
- Adding items to shopping cart
- Taking tickets at deli counter increases the number of the next ticket
- Taking an opponents piece in chess

!SLIDE

#Order independent actions
![Commutative Actions](../../images/communative.png)

!SLIDE

#Examples
- Liking a blog post
- Adding items to a set
- Grading answers to a quiz

!SLIDE

#Idempotent Operations
##Operations that get the same result when run multiple times
![Commutative Actions](../../images/communative.png)

!SLIDE

##Examples
- Upserting the same data to a database
- Assigning a value to a key in an array
- Refreshing a static webpage


!SLIDE

#Reversible Operations

![Reversible](../../images/reversible.png)

!SLIDE

#Examples
- Serializing to JSON and back
- Transferring money from one account to another
- Rotating an image 180 degrees
