import requests
from bs4 import BeautifulSoup
import plotly


### SCRAPE RAY ALLEN STATS  -- START
def get_rayallen_stats():
	r = requests.get('http://www.basketball-reference.com/players/a/allenra02.html')
	html_doc = r.text

	year_headers = [str(year) for year in range(1997,2003)] + ["2003","2003","2003"] + [str(year) for year in range(2004,2015)]
	stat_headers = ["fg_pct","fg2_pct","fg3_pct","ft_pct","pts_per_g"]
	soup = BeautifulSoup(html_doc, 'html.parser')

	stat_data = {stat : {year: [] for year in year_headers} for stat in stat_headers}
	for stat in stat_data:
		curr_stat_header = soup.find_all(attrs={"data-stat": stat})[1:]
		year_index = 0
		for attr_ in curr_stat_header:
			if not attr_.contents:
				continue
			elif year_index >= len(year_headers):
				break

			content = float(attr_.contents[0])
			if content < 1:
				content = content * 100.0
			year = year_headers[year_index]
	#		print("current year: {}".format(year))
			stat_data[stat][year].append(content)
			year_index += 1

	stat_data_final = {stat : {year: [] for year in year_headers} for stat in stat_headers}
	for stat in stat_data:
		for year in stat_data[stat]:
			content = stat_data[stat][year]
			if len(stat_data[stat][year]) > 1:
				content = sum(content)/float(len(content))
			else:
				content = content[0]
			stat_data_final[stat][year] = content
	return stat_data_final	
### SCRAPE RAY ALLEN STATS - END


### PARSE LEAGUE WIDE STATS - START
def get_shootingguard_stats():
	#http://insider.espn.com/nba/hollinger/statistics/_/position/sg/year/2014
	import pandas as pd
	stats_str = "nba-guard-stats/nba-wide-stats-"
	year_stats = {stat: {year: None for year in range(1997, 2015)} for stat in ["FG%","PTS","3P%","FT%"]}
	for i in range(1997,2015):
		end_yr = str(i)[-2:]
		curr_file = stats_str + end_yr + "-new.txt"
		data = pd.read_csv(curr_file, sep="\t", header = 0)
		year_stats["PTS"][i] = data["PTS"].mean()
		if year_stats["PTS"][i] < 1:
			year_stats["PTS"][i] *= 100.0
		year_stats["FG%"][i] = data["FG%"].mean()
		if year_stats["FG%"][i] < 1:
			year_stats["FG%"][i] *= 100.0
		year_stats["3P%"][i] = data["3P%"].mean()
		if year_stats["3P%"][i] < 1:
			year_stats["3P%"][i] *= 100.0
		year_stats["FT%"][i] = data["FT%"].mean()
		if year_stats["FT%"][i] < 1:
			year_stats["FT%"][i] *= 100.0
	return year_stats

### PARSE LEAGUE WIDE STATS - END

def graph_it(stat_data_final, sg_stats):
	import configparser
	config = configparser.ConfigParser()
	config.read('plotly-api-info.ini')
	api_key_ = config['api']['apikey']
	plotly.tools.set_credentials_file(username='dalisayd', api_key=api_key_)

	from plotly import tools
	import plotly.plotly as py
	import plotly.graph_objs as go

	stat_names = [
		("fg_pct","FG%","Average Field Goal Percentage"),
		("pts_per_g","PTS","Average Points Per Game"),
		("fg3_pct","3P%","Average 3-Point Field Goal Percentage"),
		("ft_pct","FT%","Average Free Throw Percentage")
	]
	
	for stat in stat_names:
		ra_stat = stat[0]
		all_stat = stat[1]
		title_ = stat[2]
		ra_bar = go.Bar(
			x = [year for year in range(1997,2015)],
			y = [stat_data_final[ra_stat][str(year)] for year in range(1997,2015)],
			name = "Ray Allen"
		)
		
		all_scatter = go.Scatter(
			x = [year for year in range(1997,2015)],
			y = [sg_stats[all_stat][year] for year in range(1997,2015)],
			name = "Top Shooting Guards"
		)	

		stat_data  = [ra_bar, all_scatter]

		stat_layout = go.Layout(
		    title=title_
		)
		stat_fig = go.Figure(data=stat_data, layout=stat_layout)
		py.plot(stat_fig, filename=title_, title=title_)

r = get_rayallen_stats()
s = get_shootingguard_stats()
graph_it(r,s)
