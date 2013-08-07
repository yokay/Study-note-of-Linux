###scanf的一些
---
scanf通过键盘输入的参数在buffer里面。  
比如scanf("%50s", string),由于%s指string，那么遇到space时便会停止，但是如果输入几个space，则后面的会保存在buffer，当下次执行scanf时从buffer自动取出。  
例:  
输入yokay beijing haha，则  

	scanf("%50s", string1);	//读取yokay
	scanf("%50s", stirng2);	//读取beijing，而且不等主人输入便自动将buffer内容给scanf
	scanf("%50[^\n]", stinrg2);	//读取50个字符直到换行，[^\n]表示非换行。即读取50个字符后面不是换行符。

###memory的一些
---
char *cards = "JQK";	//cards指向常字符串，不可更改，因为JQK保存在constant区域  
char cards[] = "JQK";	//JQK依然在constant区域，但是cards[]复制JQK到stack中并指向它，即cards数组的内容是从constant区域复制过来的，更改的是复制过来的string

###main的返回值
---
如果在main中有return，则在return后程序停止。

###pipe的一些
---
process1 | process2 表示将process1的standard output传输给process2的standard input  
对于重定向 < 和 > ，'<'将文件内容给pipeline的第一个程序，'>'将捕获pipeline的最后一个程序的standard output

###getopt()函数的一些
---
所需头文件：`#include <unistd.h>`  

	#include <unistd.h>
	...
	while ((ch = getopt(argc, args, "e:a")) != EOF)	//e:表示-e xx即-e后面要接参数。-a -e xxx
	switch(ch)
	{
		...
		case 'e':
			engine_count = optarg;	//optarg表示-e xxx的xxx，即-e后的参数
	}

	argc -= optind;	
	/* 
	   optind表示要跳过的options，指从args[0]到最后一个'-xxx'的string个数
		例：./add -e now -t hahah -h 45 abchj kiuh
		其optind=7，argc为9-7表示剩余2个参数 
	 */
	args += optind;	//将args跳到option后的参数，上例args指向abchj

例：

	#include <stdio.h>
	#include <unistd.h>
	
	int main(int argc, char* args[])
	{
		char* delivery = "";
		int thick = 0;
		int count = 0;
		char ch;

		while ((ch = getopt(argc, args, "d:t")) != EOF)
			switch (ch)
			{
				case 'd':
					delivery = optarg;
				break;
				case 't':
					thick = 1;
				break;
				default :
					fprintf(stderr, "Unknown option: '%s'\n", optarg);
				return 1;
			}
		argc -= optind;
		args += optind;

		if (thick)
			puts("Thick crust.");
		if (delivery[0])
			printf("To be delivered %s.\n", delivery);
		puts("Ingredients:");

		for (count = 0; count < argc; count++)
			puts(args[count]);

		return 0;
	}


编辑grub.conf   
/boot/grub/grub.conf   
在kernel /boot/vmlinuz-2.6.18-128.el5 ro root=LABEL=/ rhgb quiet vga=791   
重启电脑后通过GRUB后就可以看修改后的结果了。   
不同色彩和分辨率所对应的值   

	depth-----640x480----800x600----1024x768-----1280x1024 
	8bit---------769--------771------------773-------------775 
	15bit--------784--------787-----------790-------------793 
	16bit--------785--------788-----------791-------------794 
	24bit--------786--------789-----------792-------------795