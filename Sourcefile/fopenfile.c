#include <stdlib.h>
#include <stdio.h>

#define BUFFER_SIZE 1024
#define SRC_FILE_NAME "src_file"
#define DEST_FILE_NAME "dest_file"
#define OFFSET 4096 

int main()
{
	FILE *src_file, *dest_file;
	unsigned char buff[BUFFER_SIZE];
	int real_read_len;

	src_file = fopen(SRC_FILE_NAME, "r");
	dest_file = fopen(DEST_FILE_NAME, "w");

	if (!src_file || !dest_file)
	{
		printf("Open file error\n");
		exit(1);
	}

	fseek(src_file, -OFFSET, SEEK_END);

	while ((real_read_len = fread(buff, 1, sizeof(buff), src_file)) > 0)
	{
		fwrite(buff, 1, real_read_len, dest_file);
	}
	fclose(dest_file);
	fclose(src_file);
	return 0;
}
