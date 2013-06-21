#gcc
---
	gcc : 
	 	  -c 					生成目标文件.o gcc -c hello.s -o hello.o
		  -S 					生成汇编代码.s gcc -S hello.i -o hello.s
		  -E 					只进行预编译   gcc -E hello.c -o hello.i
		  -g 					生成的可执行程序中包含标准调试信息，用于gdb
		  -o *** 				生成**文件名的可执行文件 gcc hello.o -o hello
		  -v 					显示gcc版本号
		  -I *** 				在头文件的搜索路径列表中添加***目录

		  -static 				静态编译，链接静态库，禁止使用动态库
	 	  -shared 				生成动态库文件，进行动态编译
	 	  -L *** 				在库文件的搜索路径列表中添加***目录
	 	  -l*** 				链接为lib***.a（静态库）或者lib***.so（动态库）
	 	  -fPIC/-fpic 			生成使用相对地址的位置无关的目标代码

		  -ansi 				支持ANSI C标准
	 	  -pedantic 			允许发出ANSI C标准的警告
	 	  -pedantic-error 		允许发出ANSI C标准的错误
	 	  -w 					关闭所有警告
		  -Wall 				允许发出警告

	 	  -mcpu=*** 			***表示不同的CPU，如i386、i486、arm等

#gdb
---
在使用gdb调试前，要使用gcc -g test.c -o test编译出包含调试信息的可执行文件  
####gdb test进入调试界面  

	（gdb）l     						列出程序，list  
	（gdb）b 6    					插入断点，在line 6处插入断点。b test.c:main 标志在main函数设置断点，b main亦可。break  
	（gdb）tbreak 7 					设置临时断点，到达后自动删除  
	（gdb）delete 6					删除断点6  
	（gdb）disable 6/enable 6 		停止/激活指定断点6，可以不指定断点，此时针对所有断点。断点关闭但没有删掉。  
	（gdb）ignore 6 10 				忽略6号断点10次，可以不指定  
	（gdb）condition 6 i==1 			修改6号断点条件。b 6 if i==2设置条件断点，使用condition修改，只修改条件。  
	（gdb）info b 					查看断点  
	（gdb）bt 						查看函数或者堆栈情况，backrace  
	（gdb）r 							运行，run  
	（gdb）p n 						显示变量n的值，显示一次  
	（gdb）display n 					一直显示变量n  
	（gdb）n 							单步运行，函数直接跳过，即直接编译函数，不进入，相当于step over。next  
	（gdb）s 							单步运行，进入函数。step  
	（gdb）c 							继续运行，在查看变量或堆栈情况后，使用c就可以正常运行。continue  
	（gdb）set args ** 				指定运行参数，（args，argv[]）  
	（gdb）show args 					查看运行参数  
	（gdb）Path *** 					设定程序运行路径  
	（gdb）show paths 				查看运行路径  
	（gdb）set environment n[=2] 		设置环境变量，可以不赋值  
	（gdb）show environment [n] 		查看环境变量  
	（gdb）cd *** 					进入***目录  
	（gdb）pwd 						显示当前工作目录  
	（gdb）shell *** 					运行shell指令，如shell ls -l  
