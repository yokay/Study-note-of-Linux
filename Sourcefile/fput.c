#include <stdio.h>
main()
{
	int c;
	fputc(fgetc(stdin), stdout);
}
