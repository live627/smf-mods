# Post Notifier 

This mod will add a ribbon at the top of the topic display that will show only if there are new posts within that topic. This, as you may have guessed, uses AJAX.

It updates every 60 seconds. To change, go and open ./Themes/defautl/scripts/postnotifier.js. Replace

```
60000
```

with

```
30000
```

and that will set the timer to 30 seconds, or 30,000 milliseconds.
