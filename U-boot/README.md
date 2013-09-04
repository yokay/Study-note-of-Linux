##U-boot 文件结构
---

###cpu  
与处理器相关的文件。./cpu每个子目录下都包括`cpu.c`，`interrupt.c`，`start.S`，`u-boot.lds`。                                 
`cpu.c` 初始化CPU、设置指令Cache和数据Cache等。              
`start.S` U-boot启动时的**第一个文件**，主要做最初的系统初始化，代码重定向，设置系统堆栈，为进入U-boot的**stage2**的C程序奠定基础。                                              
`u-boot.lds` 链接脚本文件。定义了整个程序编译后的连接过程。  

###board  
---
已经支持的所有开发板的相关文件，包括**SDRAM初始化代码**、**Flash底层驱动**、**板级初始化文件**。其中的`config.mk`文件定义了`TEXT_BASE`(代码的内存实际地址，没有指定就是0)。

###comon
---
通用代码，与处理器体系结构无关。包括U-boot的命令解析代码
`/common/command.c` 、所有命令的上层代码cmd\_\*.c   、U-boot环境变量处理代码env\_\*.c等。  

###drivers
---
外围芯片驱动，网卡、USB、串口、LCD、Nand Flash等。

###disk、fs、net
---
disk：磁盘驱动的分区处理代码。  
fs：文件系统FAT、JFFS2、EXT2等。  
net：网络协议NFS、TFTP、RARP、DHCP等。  

###include
---
头文件，包括CPU的寄存器定义、文件系统、网络等。**configs子目录**下的文件是与目标板相关的配置文件。

###doc
---
U-boot的说明文档，有关于修改配置文件的内容。  

###lib\_arm
---
处理器体系相关的初始化文件。其中重要的是`board.c`，stage2的代码入口函数和相关初始化函数存放于此。(处理器体系包括avr32，mips，i386等)

###Makefile
---
MAKEALL、config.mk、rules.mk、mkconfig，编译的Makefile文件和规则文件。

###tools
---
编译U-boot映像等相关工具，mkimage用于制作bootm引导。

####api、examples
---
外部扩展应用程序的API和范例

####nand\_spl、onenand\_ipl、post
---
一些特殊构架需要的启动代码和上电自检程序代码。

####libfdt
---
支持平坦设备树(flattened device trees)的库文件。

#####CHANGELOG、CHANGELOG-before-U-Boot-1.1.5、COPYING、MAINTAINERS、README
---
一些介绍性的文档、版权说明。
