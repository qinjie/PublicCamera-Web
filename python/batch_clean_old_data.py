import json

from requests.auth import HTTPBasicAuth

import utils
from floordata import FloorData
from nodefile import NodeFile
from nodedata import NodeData

__author__ = 'zqi2'

# Run daily

if __name__ == '__main__':
    # # Read options from command line
    # argParser = argparse.ArgumentParser('API Entity')
    # argParser.add_argument('-c', '--configFile', help="Configuration file", required=False)
    # argParser.add_argument('-s', '--configSession', help="Configuration session", required=False)
    # argParser.add_argument('-u', '--username', help="Username", required=False)
    # argParser.add_argument('-p', '--password', help="Password", required=False)
    # args = argParser.parse_args()

    # timestamp_file = 'last_job_timestamp.txt'
    # touch(timestamp_file)

    logger = utils.init_logger("batch")

    username = 'manager1'
    password = '123456'
    auth = HTTPBasicAuth(username, password)

    entity = NodeFile()
    # keep max 500 records for each node
    entity.delete_older_than_n(keepn=500, auth=auth)
