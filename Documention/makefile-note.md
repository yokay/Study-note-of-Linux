#makefile
>$* 不包括扩展名的目标文件名称  
>$+ 所有依赖文件，以空格分开，以出现的先后为序，可能包含重复的依赖文件  
>$< 第一个依赖文件的名称  
>$? 所有比目标文件旧的依赖文件，以空格分开  
>$@ 目标文件的全名，包括扩展名  
>$^ 所有不重复的依赖文件，以空格分开  
>$% 若目标为归档成员，则该变量表示目标的归档成员名称  

通过实例来讲解:

	OBJS = kang.o yu1.o
	CC = gcc
	CFLAGS = -Wall -O -g

	david : $(OBJS)
	/*|        |
 	  |        |
	目标文件  依赖文件
	*/
	$(CC) $^ -o $@    //gcc kang.o yu1.o -o david

	/*
	kang.o : kang.c kang.h  //kang.o 和yu1.o 可以不写，隐式规则可以由.c自动生成.o
	$(CC) $(CFLAGS) -c $< -o $@    // gcc -Wall -O -g kang.c -o kang.o
	yu1.o : yu1.c yu1.h
	$(CC) $(CFLAGS) -c $< -o $@
	*/

	/*
	%.o : %.c    //模式规则，%.o表示所以.o文件，%.c表示所有.c文件
	$(CC) $(CFLAGS) -c $< -o $@
	*/

##命令格式
>-C *** 读入指定目录下的makefile  
>-f *** 读入当前目录下的file文件作为makefile  
>-I 忽略所有的命令执行错误  
>-I *** 指定被包含的makefile所在目录  
>-n 只打印要执行的命令，但不执行命令  
>-p 显示make变量数据库和隐含规则  
>-s 执行命令时不显示命令  
>-w 如果在make过程中改变目录，则打印当前目录名  
