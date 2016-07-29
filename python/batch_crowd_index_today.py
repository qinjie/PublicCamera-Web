import json
import os
import sys

from requests.auth import HTTPBasicAuth

import utils
from IAmodule import CrowdIdxCal
from floordata import FloorData
from nodedata import NodeData
from nodefile import NodeFile
from nodesummary import NodeSummary

__author__ = 'zqi2'


# Run every 5 min

def book_node_file(entity=None, auth=None):
    r = entity.book_one(auth)

    if r.status_code == 404:
        logger.info("No new node file found")
        return None

    if r.status_code != 200:
        return None

    # Get info from returned JSON
    return r.text


def cal_node_crowd_now(auth=None):
    # CROWD_NOW = 'CrowdNow'
    TYPE = 0

    # PHOTO_PATH = 'd:/GoogleDrive/Sites/publiccamera/upload/files/'
    # REF_PATH = 'd:/GoogleDrive/Sites/publiccamera/upload/reference/'
    PHOTO_PATH = '/var/www/html/publiccamera/upload/files/'
    REF_PATH = '/var/www/html/publiccamera/upload/reference/'

    # Get a list of unprocessed node file
    entity_nodefile = NodeFile()
    entity_nodedata = NodeData()

    while True:
        r = book_node_file(entity_nodefile, auth)
        if not r:
            # sys.exit()
            break

        j = json.loads(r)
        in_file_name = j['fileName']
        node_id = int(j['nodeId'])
        ref_file_name = '{:04}.jpg'.format(node_id)
        in_file = os.path.join(PHOTO_PATH, in_file_name)
        ref_file = os.path.join(REF_PATH, ref_file_name)

        logger.info("Processing: " + in_file)
        logger.info("Reference: " + ref_file)

        # Process file
        if not os.path.isfile(ref_file):
            logger.error("Input file not found: {0}".format(ref_file_name))
        if not os.path.isfile(in_file):
            logger.error("Reference file not found: {0}".format(in_file_name))

        j['status'] = 3
        if os.path.isfile(in_file) and os.path.isfile(ref_file):
            try:
                val = CrowdIdxCal(ref_file, in_file)
                if str(val).isdigit():
                    x = entity_nodedata.search('nodeId={0}&nodeFileId={1}&type={2}'.format(node_id, j['id'], TYPE))
                    if x:
                        logger.info(
                            'NodeData found: nodeId={0}&nodeFileId={1}&type={2}'.format(node_id, j['id'], TYPE))
                        j['status'] = 2
                    else:
                        payload = {'nodeId': node_id, 'type': TYPE, 'value': str(val),
                                   'nodeFileId': j['id'], 'remark': j['created'],
                                   'created': j['created'], 'modified': j['modified']}
                        r = entity_nodedata.create(payload, auth)
                        if r.status_code != 201:
                            logger.error("Error - saving crowd index: {0}. {1}".format(json.dumps(payload), r.text))
                        else:
                            j['status'] = 2
            except Exception as e:
                logger.exception("Calculate Crowd Index Error")

        # Update the status to "Done"
        del j['fileUrl']
        del j['thumbnailUrl']
        r = entity_nodefile.update(j, auth)
        if r.status_code != 200:
            logger.error(r.text)


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

    # Calculate CrowdIndex of each photo
    cal_node_crowd_now(auth)

    # Find average CrowdIndex of a node
    node_summary = NodeSummary()
    node_summary.node_crowd_average(auth)

    entity = FloorData()
    entity.floor_crowd_today(auth)
