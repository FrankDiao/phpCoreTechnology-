#### 1. Cookie
##### 1.1 Cookie的基本概念及设置
&ensp;&ensp; 首先需要理解的是cookie不是任何一种语言的技术，而是独属于客户端（包括但不限于浏览器）的一种存储机制。所以说不管是PHP或是JavaScript都不能直接操作cookie，而是通过HTTP协议‘通知’到浏览器，由浏览器进行操作。
同时也可以将cookie设置为仅HTTP协议才能访问（例如在php中的`setcookie`函数中设置`httpOnly`参数），设置之后js等都不能操作该cookie。
##### 1.2 Cookie的存储机制应用
&ensp;&ensp; **cookie没有显式的删除方式**，只能通过设置过期时间的方式来删除cookie。如果cookie没有设置过期时间则存储在内存中，即随着浏览器的关闭而清除。如果设置了过期时间，在过期之前cookie都是存储在电脑的硬盘中。

&ensp;&ensp; 需要注意的是一个域名下cookie的存储数量是有限制的，根据浏览器的不同限制也不相同（例如IE8中可存储50个，火狐可存储150个），另外一个cookie的最大存储字节有限制的，限制也是由浏览器决定的。

&ensp;&ensp; cookie的应用场景一般来说都是用于记住登录状态、浏览历史等这些不算敏感但能提升用户体验的东西。需要注意一点，上面提到过cookie与服务端的通信是基于HTTP的，所以cookie的上行下载对带宽的消耗较高。
##### 1.3 Cookie跨域与P3P
&ensp;&ensp; 正常的cookie只能在创建它的应用中共享，实现cookie的跨域，主要是为了实现单点登录的需求。P3P是cookie跨域的最简单的实现方式，具体实现方式可自行搜索，懒得写了。

#### 2. Session
&ensp;&ensp; Session即会话，是一钟双向的、持续性的连接。Seeion和Cookie在本质上并没有什么区别，都是为了解决HTTP协议的局限性而提出的保持客户端与服务端会话连接状态的一种机制。Session的实现方式有多种，例如URL重写、Cookie，通过在Cookie中设置SessionID传递等方式。
##### 2.1 Session的基本概念及设置
&ensp;&ensp; 和Cookie一样Session也是一个通用标准，在不同的的语言中实现的方式也不同。就web站点的角度来讲，session是指客户端从打开网页到关闭浏览器的这段时间的会话，所以从这里可以看出Session实际上一种是时间概念。Seeion可以实现在程序上下文中传递变量、用户身份认证、记录程序状态等。
&ensp;&ensp; PHP的默认Session由文件形式的，即保存在服务器硬盘中的文件，每个session一个文件，文件内容格式如下：
```
变量名|类型:长度:值;
```
##### 2.2 Session的工作原理
&ensp;&ensp; 我们知道HTTP协议是不能保存客户端和服务端的会话状态的，Session和Cookie都是为了解决这个缺陷而被设计出来的。
&ensp;&ensp; 在PHP中，Session是通过一个叫做PHPSESSION的Cookie来保持会话的，即服务端生成一个Session文件存储在服务器中，这个文件的文件名就是SessionID，服务端将这个SessionID设置客户端的一个名为PHPSESSION的Cookie的值，在HTTP request 和 HTTP reponse中传来传去，以此来实现会话状态的保持。
##### 2.3 Session与Cookie的关系
&ensp;&ensp; 上文说到Session是将SessionID设置为一个名叫PHPSESSION的Cookie，然后在请求和响应中带上它来保持会话状态的。那么，如果客户端禁用了Cookie的话Session还能否传递？答案肯定是可以的，但是却不能通过Cookie的方式传递了，传递Session的实现的方式有很多种，例如重写URL，如：
```
domain.com/index.php/SESSIONID=xxxxxxx
```
或者在表单中传递，还可以存储在localStorage中使用JS传递等等...
#### 3. Q & A
##### 3.1 Cookie运行在客户端，Session运行在服务端对吗？
&ensp;&ensp; 不完全对。Cookie运行在客户端，由客户端进行管理。Session虽然运行在服务端，但是SessionID是作为一个Cookie运行在客户端的。
##### 3.2 浏览器禁用Cookie，Cookie就不能用了，但Session不受影响对吗？
&ensp;&ensp;错。浏览器禁用Cookie，Cookie就不能用了，同时SessionID也不能通过Cookie传递了，但可以通过其他方式传递，上文有讲。
##### 3.3 关闭浏览器后，Cookie和Session都会清除，对吗？
&ensp;&ensp; 错。只有存储在内存中的Cookie会在浏览器关闭后被清除，存储在硬盘中的不会。而Session在浏览器关闭后也不会消失，除非正常退出，在代码中使用unset删除Session，否则Session可能会被回收，也可能永远残留在系统中。
##### 3.4 Session比Cookie更安全吗？不应该大量使用Cookie吗？
&ensp;&ensp;错。Cookie确实存在一些不安全因素，但即使突破了前端验证还有后端保障安全。在上文讲到过通常情况下Session和Cookie是绑定的，劫持了Cookie就等于劫持了Session。只能说一切都要看设计。
##### 3.5 Session是创建在服务端的，所以应该多用Cookie对吗？
&ensp;&ensp;错。Cookie可以提高用户体验。但是却会加大网络间的传输流量，所以应该在Cookie中仅保存必要的数据。
##### 3.6 如果把别人的Cookie复制到我的电脑中（相同的浏览器），是否也能登陆别人的账号呢？如何防范？
&ensp;&ensp;是的。这属于Cookie劫持的一种。要避免这种情况可以在Cookie中针对IP、UA等加上特殊的校验方式，然后和服务端进行对比。``