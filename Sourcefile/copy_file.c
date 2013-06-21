#include <unistd.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <stdlib.h>
#include <stdio.h>

#define BUFFER_SIZE 1024
//#define SRC_FILE_NAME "src_file"
//#define DEST_FILE_NAME "dest_file"
//#define OFFSET 10240

int main (int argc, char *argv[])
{
	int src_file, dest_file;
	unsigned char buff[BUFFER_SIZE];
	int real_read_len;
	int offset = 0;

	printf("argc:%d\n",argc);
	printf("The source-file is %s\n", argv[1]);


	if (argc == 1)
	{
		printf("Please input source-file and dest-file.");
		exit(0);
	}
	if (argc == 2)
	{
		printf("The dest-file is ./source_file_temp\n");
		src_file = open(argv[1], O_RDONLY);
		dest_file = open("./source_file_temp", O_WRONLY | O_CREAT | O_APPEND,0644);
	}
	if (argc == 3)
	{
		printf("The dest-file is %s\n",argv[2]);
		src_file = open(argv[1], O_RDONLY);
		dest_file = open(argv[2], O_WRONLY | O_APPEND, 0644);
	}
//	src_file = open(SRC_FILE_NAME, O_RDONLY);
//	dest_file = open(DEST_FILE_NAME,
//					O_WRONLY|O_CREAT,
//					S_IRUSR|S_IWUSR|S_IRGRP|S_IROTH);
	if (src_file < 0 )
	{
		printf("Open source-file error!\n");
		exit(1);
	}
	if (dest_file < 0 )
	{
		printf("Open dest-file error!\n");
		exit(1);
	}


	lseek(src_file, offset, SEEK_SET);
	printf("copy src_file from %d, and offset is %d\n", SEEK_SET, offset);
	
	

	while  ((real_read_len = read(src_file, buff, sizeof(buff))) > 0)
	{
		printf("sizeof(buff) is %d\nreal_read_len is :%d\n",sizeof(buff), real_read_len);
		write(dest_file, buff, real_read_len);
	}
	close(dest_file);
	close(src_file);
	return 0;
}
