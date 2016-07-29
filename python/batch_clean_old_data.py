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
    entity.keep_latest_n_each(cnt=20, auth=auth)

    entity = NodeData()
    # resp = entity.search("label=CrowdNow", auth)
    resp = entity.search("type=0", auth)
    if resp.status_code == 200:
        j = json.loads(resp.text)
        if 'items' not in j:
            raise ValueError("No items in returned json")

        # Create a setting map for this floor
        for obj in j['items']:
            r = entity.delete(obj['id'], auth)
