Yokay Linux 事务所
=================
/* 以=号为分界线的为位最高阶标题 */
Yokay Linux
----------------
/* 以-号为分界线的为第二阶标题 */

#Yokay Linux 事务所
/* #号表示1阶标题，##表示2阶，###...最多可以表示6阶   
单个回车视为空格，连续回车为分段。行尾加两个空格是段内换行。 */

--------------------------------------------------------------

>This is a clockquote.
>
>This is the seocond paragraph in the blockquote.
>
>##This is an H2 in a blockquote.

-------------------------------------------------------------
Some of these words *are emphasized*.  
Some of these words _are emphasized_.  

/* *号、_号之间语句为斜的 */

Some of these words __are emphasized__.
Some of these words **are emphasized**.

/* **号、__号之间语句为加粗的 */

---------------------------------------------------------------

* RED
* BLUE  
* GREEN  
||
+ RED
+ BLUE  
+ GREEN  
||
- RED  
- BLUE  
- GREEN  
||
- RED
 + BLUE
 + BLUE
- GREEN

/* 无序列表 */

1. RED  
2. BLUE  
3. GREEN  

/* 有序列表 */

------------------------------------------------

This is [Yokay Linux 事务所](www.yokay.info).

This is [Yokay Linux 事务所](www.yokay.info "Yokay Linux 事务所").

/* ""中内容为标签，鼠标停在链接上时浮出文字 */

I get 10 times more traffic from [Google][1] than from [Yahoo][2] or [Msn][3].
[1]: www.google.com "Google"
[2]: www.yahoo.com "Yahoo"
[3]: www.msn.com "Msn"

/* [N]其中N可以用字母或者单词代替 */

---------------------------------------------------

![Yokay](C:\Users\Wee Yokay\SkyDrive\yokay\xjb\touxian3g.jpg "Yokay")

![Yokay][1]
[1]: C:\Users\Wee Yokay\SkyDrive\yokay\xjb\touxian3g.jpg "Yokay"


/* 插入图片形式，不一定靠谱 */

--------------------------------------------------

    #include <stdio.h>
	void main()
	{
		printf("行的开头4个空格，表示程序代码.");
	}  

```ruby
	APP_NAME = "YOUR_APP_NAME"  
```

`YOKAY`  

/* 开头以4个空格或者Tab开始，编辑程序代码 */

