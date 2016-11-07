#Cleans up stats text files.

# Clean up 97 through 99 with this method.
def cleanup_newline_issue():
	fix_files = ["nba-guard-stats/nba-wide-stats-97","nba-guard-stats/nba-wide-stats-98","nba-guard-stats/nba-wide-stats-99"]
	for fix_file in fix_files:
		file_name = "{}.txt".format(fix_file)
		f = open(file_name,"r")
		lines = f.readlines()
		new_f = []
		f_index = 1
		new_f.append(lines[0])
		for l_index in range(1,len(lines)):
			if f_index % 2 == 0:
				curr_line = lines[l_index]
				prev_line = lines[l_index - 1]
				new_line = prev_line.rstrip() + "\t" + curr_line
				new_f.append(new_line)
			f_index += 1

		f.close()

		file_name_new = "{}-new.txt".format(fix_file)
		f_ = open(file_name_new,"w")
		f_.write("".join(new_f))
		f_.close()

# Clean up 2000 to 2014 with this method
def cleanup_multipleheader_issue():
	import pandas as pd
	fix_files = ["nba-guard-stats/nba-wide-stats-"+str(year)[-2:]  for year in range(2000,2015)]	
	
	for fix_file in fix_files:
		
		f = open(fix_file + ".txt","r")
		lines = f.readlines()
		new_f = []
		new_f.append(lines[0])
		for l_index in range(1,len(lines)):
			columns = lines[l_index].split("\t")
			if not columns[0].strip().isdigit():
				continue
			new_f.append(lines[l_index])
		f.close()

		file_name_new = "{}-new.txt".format(fix_file)
		f_ = open(file_name_new,"w")
		f_.write("".join(new_f))
		f_.close()

cleanup_multipleheader_issue()
