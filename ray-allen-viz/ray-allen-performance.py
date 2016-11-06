import requests
from bs4 import BeautifulSoup
import plotly


### SCRAPE RAY ALLEN STATS  -- START
def get_rayallen_status():
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
			
### PARSE LEAGUE WIDE STATS - END

import configparser
config = configparser.ConfigParser()
config.read('plotly-api-info.ini')
api_key_ = config['api']['api-key']
plotly.tools.set_credentials_file(username='dalisayd', api_key=api_key_)

from plotly import tools
import plotly.plotly as py
import plotly.graph_objs as go


fg_pct_per_game = go.Bar(
	x = [year for year in range(1997,2015)],
	y = [stat_data_final["fg_pct"][str(year)] for year in range(1997,2015)],
	name = "Average Field Goal % Per Game"
)


points_per_game = go.Bar(
	x = [year for year in range(1997,2015)],
	y = [stat_data_final["pts_per_g"][str(year)] for year in range(1997,2015)],#stat_data_final["pts_per_g"]]
	name = "Average Points Per Game"
)

ft_pct_per_game = go.Bar(
	x = [year for year in range(1997,2015)],
	y = [stat_data_final["ft_pct"][str(year)] for year in range(1997,2015)],#stat_data_final["ft_pct"]]
	name = "Free Throw % Per Game"	
)
fg3_pct_per_game = go.Bar(
	x = [year for year in range(1997,2015)],
	y = [stat_data_final["fg3_pct"][str(year)] for year in range(1997,2015)],#stat_data_final["fg3_pct"]]
	name = "Average 3-PT Field Goal % Per Game"
)

fig = tools.make_subplots(rows=2, cols=2, subplot_titles=('Average Points Per Game','Average Free Throw Percentage','Average Field Goal %', 'Average 3-Point Field Goal %'))

#fig = [points_per_game,l_points_per_game]
fig.append_trace(points_per_game, 1, 1)
fig.append_trace(ft_pct_per_game, 1, 2)
fig.append_trace(fg_pct_per_game, 2, 1)
fig.append_trace(fg3_pct_per_game, 2, 2)

fig['layout'].update(height=400, width=600, title='Ray Allen\'s Performance from 1997 to 2014')

plot_url = py.plot(fig, filename='ray-allen-performance')
