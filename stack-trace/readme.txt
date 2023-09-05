[font=Times New Roman][color=#cc6633][size=4][b]Stack Trace[/b] 1.0[/size][/color][/font]
[hr]
[url=http://opensource.org/licenses/MIT][img]https://camo.githubusercontent.com/d7b0ca6383644d5ac81e234f8d2249b731a1407b/687474703a2f2f696d672e736869656c64732e696f2f62616467652f6c6963656e73652d4d49542d3030393939392e737667[/img][/url] [url=https://www.paypal.me/JohnRayes][img]https://camo.githubusercontent.com/e03e24ac37094afa6d1d089fc32de8027e9b4988/687474703a2f2f696d672e736869656c64732e696f2f62616467652f50617950616c2d242d3030393936362e737667[/img][/url]
[hr]
Show the backtrace in the error log

[font=Times New Roman][color=#cc6633][size=3][b]Introduction:[/b][/size][/color][/font]
[hr]
In simple terms, a stack trace is a list of the method calls that the application was in the middle of when an error was encountered.

Tracing the call stack is important for complex codebases such as SMF because doing so helps software engineers and other developers find bugs in the program. Because of the nature of modern code syntax, and the complexity of the average project, looking for bugs can be very difficult. A stack trace is just one of many tools that can be useful in finding bugs or glitches.

This mod works by calling [tt]debug_backtrace()[/tt] from [tt]log_error[/tt] to ask PHP to trace the call stack. The results are then serialized into a database field for viewing by the admin when the error log is opened.